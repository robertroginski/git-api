<?php

namespace App\Model;

use App\Repository\GitRepositoryInterface;

class GitModel{

    private $id;

    private $node_id;

    private $name;

    private $full_name;

    private $private;

    private $owner;

    private $html_url;

    private $description;

    private $created_at;

    private $updated_at;

    private $stargazers_count;

    private $watchers_count;

    private $language;

    private $forks;

    private $open_issues;

    private $watchers;

    private $subscribers_count;


    /**
     * GitModel constructor.
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        if($data){
            $this->setData($data);
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNodeId(): string
    {
        return $this->node_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }

    /**
     * @return array
     */
    public function getOwner(): array
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getHtmlUrl(): string
    {
        return $this->html_url;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * @return int
     */
    public function getStargazersCount(): int
    {
        return $this->stargazers_count;
    }

    /**
     * @return int
     */
    public function getWatchersCount(): int
    {
        return $this->watchers_count;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return int
     */
    public function getForks(): int
    {
        return $this->forks;
    }

    public function getOpenIssues(): int
    {
        return $this->open_issues;
    }

    /**
     * @return int
     */
    public function getWatchers(): int
    {
        return $this->watchers;

    }

    /**
     * @return int
     */
    public function getSubscribersCount(): int
    {
        return $this->subscribers_count;
    }


    /**
     * @param array $data
     */
    public function setData(array $data = array())
    {
        foreach($data as $k => $v)
        {
            if(property_exists($this, $k)){
                $this->$k = $v;
            }
        }
    }




}
