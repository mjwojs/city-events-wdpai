<?php

use PHPUnit\Framework\TestCase;

require_once 'src/repository/UserRepository.php';
require_once 'src/models/User.php';

class UserRepositoryTest extends TestCase {
    private $userRepository;

    protected function setUp(): void {
        $this->userRepository = new UserRepository();
    }

    public function testSaveUser() {
        $user = new User('test@example.com', 'password', 'John', 'Doe', 'johndoe', 'New York', null);
        $this->userRepository->save($user);

        $savedUser = $this->userRepository->findByEmail('test@example.com');
        $this->assertEquals('John', $savedUser->getFirstName());
        $this->assertEquals('Doe', $savedUser->getLastName());
    }

    public function testFindByEmail() {
        $user = $this->userRepository->findByEmail('test@example.com');
        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testUpdateProfilePicture() {
        $user = $this->userRepository->findByEmail('test@example.com');
        $this->assertNotNull($user);

        $profilePicture = file_get_contents('public/images/avatar.jpg');
        $this->userRepository->updateProfilePicture($user->getId(), $profilePicture);

        $updatedUser = $this->userRepository->find($user->getId());
        $this->assertNotNull($updatedUser->getProfilePicture());
    }

    // public function testAddAndGetFriends() {
    //     $user1 = new User('user1@example.com', 'password', 'User1', 'One', 'userone', 'City1', null);
    //     $user2 = new User('user2@example.com', 'password', 'User2', 'Two', 'usertwo', 'City2', null);
    //     $this->userRepository->save($user1);
    //     $this->userRepository->save($user2);

    //     $savedUser1 = $this->userRepository->findByEmail('user1@example.com');
    //     $savedUser2 = $this->userRepository->findByEmail('user2@example.com');

    //     $this->userRepository->addFriend($savedUser1->getId(), $savedUser2->getId());

    //     $friends = $this->userRepository->getFriends($savedUser1->getId());
    //     $this->assertCount(1, $friends);
    //     $this->assertEquals('user2@example.com', $friends[0]->getEmail());
    // }
}
