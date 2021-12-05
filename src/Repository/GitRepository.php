<?php

namespace App\Repository;

use App\Model\GitModel;
use Github\Client;

/**
 * Class GitRepository
 * @package App\Repository
 */
class GitRepository implements GitRepositoryInterface {

    /**
     * @var Client
     */
    private $_client;


    /**
     * GitRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->_client = $client;
    }


    /**
     * @link http://developer.github.com/v3/repos/
     *
     * @param string $repoUsername
     * @param string|null $repoName
     * @return GitModel|null
     */
    public function get(string $repoUsername, string $repoName)
    {
        try {
            $response = $this->_client->api('repository')->show($repoUsername, $repoName);
        } catch (\Exception $e){
            return null;
        }

        $gitModel = new GitModel($response);

        return $gitModel;
    }


    /**
     * @link https://docs.github.com/en/rest/reference/search#search-issues-and-pull-requests
     *
     * @param string $repoUsername
     * @param string $repoName
     * @return |null
     */
    public function getLatestRelease(string $repoUsername, string $repoName)
    {
        try {
            $response = $this->_client->api('repository')->releases()->latest($repoUsername, $repoName);
        } catch (\Exception $e){
            return null;
        }

        return $response;
    }

    /**
     * @link https://docs.github.com/en/rest/reference/search#search-issues-and-pull-requests
     *
     * @param string $repoUsername
     * @param string $repoName
     * @return |null
     */
    public function getCountPullRequestsOpen(string $repoUsername, string $repoName)
    {
        try {
            $response = $this->searchIssues('is:pr is:open repo:'.$repoUsername.'/'.$repoName);
        } catch (\Exception $e){
            return null;
        }

        return $response['total_count'] ?? null;
    }

    /**
     * @link https://docs.github.com/en/rest/reference/search#search-issues-and-pull-requests
     *
     * @param string $repoUsername
     * @param string $repoName
     * @return |null
     */
    public function getCountPullRequestsClosed(string $repoUsername, string $repoName)
    {
        try {
            $response = $this->searchIssues('is:pr is:closed repo:'.$repoUsername.'/'.$repoName);
        } catch (\Exception $e){
            return null;
        }

        return $response['total_count'] ?? null;
    }


    /**
     * @link http://developer.github.com/v3/issues/#list-issues
     *
     * @param $q
     * @param string $sort
     * @param string $order
     * @return |null
     */
    public function searchIssues($q, $sort = 'updated', $order = 'desc')
    {
        try {
            $response = $this->_client->api('search')->issues($q, $sort, $order);
        } catch (\Exception $e){
            return null;
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

}
