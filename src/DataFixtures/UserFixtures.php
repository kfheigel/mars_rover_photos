<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\ApiToken;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@user.mail');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
            $user,
            'secret'
        ));
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified('1');

        $manager->persist($user);
        $manager->flush();

        $token = new ApiToken($user);
        $manager->persist($token);
        $manager->flush();
    }
}
