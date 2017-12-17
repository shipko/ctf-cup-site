<?php
namespace SibirCtfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="mentor")
 */
class Mentor
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $fio;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mail;

    /**
     * @ORM\OneToOne(targetEntity="User", mappedBy="mentor", cascade={"persist"})
     */
    private $profile = null;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $size;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fio
     *
     * @param string $fio
     *
     * @return $this
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * Get fio
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     *
     */
    public function getMail()
    {
        return $this->mail;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * Set profile
     *
     * @param string $profile
     *
     * @return Mentor
     */
    public function setProfile(User $profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     * @return string
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Mentor
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }


}