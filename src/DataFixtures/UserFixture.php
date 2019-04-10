<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {

        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function($i) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->userPasswordEncoder->encodePassword(
                $user,
                'engage'
            ));

            if ($this->faker->boolean) {
                $user->setTwitterUsername($this->faker->userName);
            }

            return $user;
        });

        $this->createMany(3, 'admin_users', function($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@email.com', $i));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->userPasswordEncoder->encodePassword(
                $user,
                'engage'
            ));

            if ($this->faker->boolean) {
                $user->setTwitterUsername($this->faker->userName);
            }

            return $user;
        });

        $manager->flush();
    }
}
