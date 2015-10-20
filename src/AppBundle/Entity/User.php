<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class User
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", groups={"registration"})
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=true)),
 * })
 *
 * @ORM\HasLifecycleCallbacks()
 *
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $apiKey;

    /**
     * @var
     * @Email(groups={"registration"})
     * @NotBlank(groups={"registration"})
     */
    protected $email;

    /**
     * @var
     * @Length(min="5", groups={"registration"})
     * @NotBlank(groups={"registration"})
     */
    protected $plainPassword;

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @ORM\PrePersist()
     */
    public function setApiKey($apiKey)
    {
        if (is_object($apiKey)) {
            $this->apiKey = sha1($this->email.microtime(). rand(1, 100000));
            return;
        }

        $this->apiKey = $apiKey;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    #@Choice(choices={"m", "f"}, groups={"registration", "edit"})


}