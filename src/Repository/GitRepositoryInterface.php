<?php

namespace App\Repository;

/**
 * Interface GitRepositoryInterface
 * @package App\Repository
 */
interface GitRepositoryInterface{

    public function get(string $repositoryName, string $repoName);

    public function getLatestRelease(string $repoUsername, string $repoName);

    public function getCountPullRequestsOpen(string $repoUsername, string $repoName);

    public function getCountPullRequestsClosed(string $repoUsername, string $repoName);

    public function searchIssues($q);

    public function getStatsData(string $repoUsername, string $repoName);

}
