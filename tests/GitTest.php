<?php

namespace App\Tests;

use App\Repository\GitRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GitTest extends KernelTestCase
{
    public function testGitStatsData(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $gitRepository = $container->get(GitRepositoryInterface::class);

        $username = 'symfony/symfony';

        $gitStatsData = $gitRepository->getStatsData($username);

        $this->assertTrue($gitStatsData ? true : false);
        $this->assertTrue($gitStatsData['fullname'] == $username);
    }

}
