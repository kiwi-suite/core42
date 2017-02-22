<?php
namespace Core42Test\Asset\Hash;

use Core42\Asset\Hash\CommitHashInterface;
use Core42\Asset\Hash\LongCommitHash;
use PHPUnit\Framework\TestCase;

class LongCommitHashTest extends TestCase
{
    /**
     * @var CommitHashInterface
     */
    protected $commitHash;

    /**
     *
     */
    public function setUp()
    {
        $this->commitHash = new LongCommitHash();
    }

    public function testGetHash()
    {
        $this->assertEquals($this->commitHash->getHash(), "");

        $this->setRevisionJson(json_encode([
            "revision_type" => "git",
            "revision_hash" => "6a819ffa899bfe7bc8acb6b2e807913df981ac42",
            "revision_hash_short" => "6a819ff"
        ]));
        $this->assertEquals("6a819ffa899bfe7bc8acb6b2e807913df981ac42", $this->commitHash->getHash());

        $this->setRevisionJson(json_encode([]));
        $this->assertEquals("", $this->commitHash->getHash());


        $this->setRevisionJson('{"key":"invalid_json');
        $this->assertEquals("", $this->commitHash->getHash());
    }

    protected function setRevisionJson($content)
    {
        if (!file_exists('resources/version/revision.json')) {
            mkdir('resources/version', 0777, true);
        }

        file_put_contents('resources/version/revision.json', $content);
    }

    public static function tearDownAfterClass()
    {
        @unlink('resources/version/revision.json');
        @rmdir('resources/version/');
        @rmdir('resources/');
    }
}
