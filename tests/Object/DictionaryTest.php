<?php

namespace Utility\Tests\Object;

use Utility\Exception\NonStaticCallException;
use Utility\Tests\Fixture\UserRoleDictionary;
use Utility\Tests\Fixture\UserStatusDictionary;
use Utility\Tests\TestCase;

class DictionaryTest extends TestCase
{
    public function testConstructor()
    {
        try {
            new UserRoleDictionary();
            $this->fail('Expected exception not thrown');
        } catch(NonStaticCallException $e){
            $this->assertInstanceOf('\\Utility\\Exception\\NonStaticCallException', $e);
            $this->assertEquals('Non static call is disabled.', $e->getMessage());
        }
    }

    public function testCache()
    {
        $roles = UserRoleDictionary::getKeys();
        $statuses = UserStatusDictionary::getKeys();

        $this->assertCount(3, $roles);
        $this->assertCount(4, $statuses);
    }

    public function testGetKeys()
    {
        $actual = UserRoleDictionary::getKeys();

        $this->assertInternalType('array', $actual);
        $this->assertContains(UserRoleDictionary::ROLE_USER, $actual);
        $this->assertContains(UserRoleDictionary::ROLE_MODERATOR, $actual);
        $this->assertContains(UserRoleDictionary::ROLE_ADMIN, $actual);
        $this->assertCount(3, $actual);
    }

    public function testGetTitles()
    {
        $actual = UserRoleDictionary::getTitles();

        $this->assertInternalType('array', $actual);
        $this->assertArrayHasKey(UserRoleDictionary::ROLE_USER, $actual);
        $this->assertArrayHasKey(UserRoleDictionary::ROLE_MODERATOR, $actual);
        $this->assertArrayHasKey(UserRoleDictionary::ROLE_ADMIN, $actual);
        $this->assertEquals(3, sizeof($actual));

        $this->assertEquals('User', $actual[UserRoleDictionary::ROLE_USER]);
        $this->assertEquals('Moderator', $actual[UserRoleDictionary::ROLE_MODERATOR]);
        $this->assertEquals('Admin', $actual[UserRoleDictionary::ROLE_ADMIN]);
    }

    public function testGetTitle()
    {
        try {
            UserRoleDictionary::getTitle('asd');
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "asd" not found.', $e->getMessage());
        }

        try {
            UserRoleDictionary::getTitle(null);
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
            $this->assertEquals('Element with key "" not found.', $e->getMessage());
        }

        $actual = UserRoleDictionary::getTitle(UserRoleDictionary::ROLE_USER);
        $this->assertEquals('User', $actual);

        $actual = UserRoleDictionary::getTitle(UserRoleDictionary::ROLE_MODERATOR);
        $this->assertEquals('Moderator', $actual);

        $actual = UserRoleDictionary::getTitle(UserRoleDictionary::ROLE_ADMIN);
        $this->assertEquals('Admin', $actual);
    }
}