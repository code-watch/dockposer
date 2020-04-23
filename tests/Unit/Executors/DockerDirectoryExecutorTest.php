<?php

declare(strict_types=1);

namespace Unit\Executors;

use Ntavelis\Dockposer\Contracts\FilesystemInterface;
use Ntavelis\Dockposer\DockposerConfig;
use Ntavelis\Dockposer\Enum\ExecutorStatus;
use Ntavelis\Dockposer\Executors\DockerDirectoryExecutor;
use Ntavelis\Dockposer\Message\ExecutorResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DockerDirectoryExecutorTest extends TestCase
{
    /**
     * @var DockerDirectoryExecutor
     */
    private $executor;
    /**
     * @var FilesystemInterface|MockObject
     */
    private $filesystem;

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->createMock(FilesystemInterface::class);
        $config = new DockposerConfig(__DIR__, __DIR__ . '/demoapp');
        $this->executor = new DockerDirectoryExecutor($this->filesystem, $config);
    }

    /** @test */
    public function itWillCreateADirectoryToHoldTheDockerFiles(): void
    {
        $result = $this->executor->execute();

        $this->assertSame('Created docker directory, at ./docker', $result->getResult());
        $this->assertSame(ExecutorStatus::SUCCESS, $result->getStatus());
    }

    /** @test */
    public function ifTheDirectoryDoesNotExistItWillReturnTrueWhenAskedIfItShouldBeExecuted(): void
    {
        $bool = $this->executor->shouldExecute();

        $this->assertTrue($bool);
    }

    /** @test */
    public function ifTheDirectoryDoesExistItWillReturnFalseWhenAskedIfItShouldBeExecuted(): void
    {
        $config = new DockposerConfig(__DIR__, __DIR__);
        $executor = new DockerDirectoryExecutor($this->filesystem, $config);

        $bool = $executor->shouldExecute();

        $this->assertFalse($bool);
    }
}
