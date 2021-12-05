<?php

namespace App\Repository;

use App\Model\GitModel;
use Github\Client;
use Symfony\Component\HttpKernel\KernelInterface;

class GitRepository implements GitRepositoryInterface {

    /**
     * @var KernelInterface
     */
    private $_kernel;

    /**
     * @var Client
     */
    private $_client;

    /**
     * GitRepository constructor.
     * @param Client $client
     */
    public function __construct(KernelInterface $kernel, Client $client)
    {
        $this->_kernel = $kernel;
        $this->_client = $client;
    }


    /**
     * @param string $repoUsername
     * @param string|null $repoName
     * @return GitModel|null
     */
    public function get(string $repoUsername, string $repoName)
    {
        $filename = $this->getRepoCacheFilname($repoUsername, $repoName);

        if(file_exists($filename)){
            $response = json_decode(file_get_contents($filename), true);
        } else{
            try {
                $response = $this->_client->api('repository')->show($repoUsername, $repoName);
                file_put_contents($filename, json_encode($response));
            } catch (\Exception $e){
                return null;
            }
        }

        $gitModel = new GitModel($response);

        return $gitModel;
    }


    public function getLatestRelease(string $repoUsername, string $repoName)
    {
        $filename = $this->getRepoCacheFilname($repoUsername, $repoName, 'latest-release');

        if(file_exists($filename)){
            $response = json_decode(file_get_contents($filename), true);
        } else {

            try {
                $response = $this->_client->api('repository')->releases()->latest($repoUsername, $repoName);
                file_put_contents($filename, json_encode($response));
            } catch (\Exception $e){
                return null;
            }
        }

        return $response;
    }

    public function getCountPullRequestsOpen(string $repoUsername, string $repoName)
    {
        try {
            $response = $this->searchIssues('is:pr is:open repo:'.$repoUsername.'/'.$repoName);
        } catch (\Exception $e){
            return null;
        }

        return $response['total_count'] ?? null;
    }

    public function getCountPullRequestsClosed(string $repoUsername, string $repoName)
    {
        try {
            $response = $this->searchIssues('is:pr is:closed repo:'.$repoUsername.'/'.$repoName);
        } catch (\Exception $e){
            return null;
        }

        return $response['total_count'] ?? null;
    }


    public function searchIssues($q, $sort = 'updated', $order = 'desc')
    {
        $filename = $this->_kernel->getProjectDir().'/var/repo/search-issues-'.md5($q.$sort.$order).'.json';

        if(file_exists($filename)){
            $response = json_decode(file_get_contents($filename), true);
        } else {

            try {
                $response = $this->_client->api('search')->issues($q, $sort, $order);
                file_put_contents($filename, json_encode($response));
            } catch (\Exception $e){
                return null;
            }
        }

        return $response;
    }


    /**
     * @param string $repoUsername
     * @param string|null $repoName
     * @return array|null
     */
    public function getStatsData(string $repoUsername, string $repoName = null)
    {
        if(!$repoName){
            $repoName = trim(strstr($repoUsername, '/'), '/');
            $repoUsername = strstr($repoUsername, '/', true);
        }

        if(!$repoUsername || !$repoName){
            return null;
        }

        $gitModel = $this->get($repoUsername, $repoName);

        if(!$gitModel){
            return null;
        }

        $gitData = [];
        $gitData['fullname'] = $gitModel->getFullName();
        $gitData['language'] = $gitModel->getLanguage();
        $gitData['description'] = $gitModel->getDescription();
        $gitData['html_url'] = $gitModel->getHtmlUrl();
        $gitData['forks'] = $gitModel->getForks();
        $gitData['subscribers_count'] = $gitModel->getSubscribersCount();
        $gitData['stargazers_count'] = $gitModel->getStargazersCount();
        $gitData['watchers'] = $gitModel->getWatchers();

        $latestRelease = $this->getLatestRelease($repoUsername, $repoName);
        $gitData['latest_release_date'] = $latestRelease['created_at'] ?? null;

        $gitData['count_pull_request_open'] = $this->getCountPullRequestsOpen($repoUsername, $repoName);
        $gitData['count_pull_request_closed'] = $this->getCountPullRequestsClosed($repoUsername, $repoName);

        return $gitData;
    }


    private function getRepoCacheFilname($username, $reponame, $type = '')
    {
        return $this->_kernel->getProjectDir().'/var/repo/'.trim($username.'-'.$reponame.'-'.$type, '-').'.json';
    }

}
