<?php
namespace SibirCtfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="member")
 */
class Member
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
     * @ORM\ManyToOne(targetEntity="SibirCtfBundle\Entity\Team", inversedBy="members", cascade={"remove"})
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    private $team;

    /**
     * @ORM\OneToOne(targetEntity="User", mappedBy="member", cascade={"persist"})
     */
    private $profile = null;

    /**
     * @var Boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCaptain = 0;

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
     * @return Member
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
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }
    /**
     * @param Team $team
     *
     * @return Member
     */
    public function setTeam($team)
    {
        $this->team = $team;
        return $this;
    }

    /**
     * Set profile
     *
     * @param string $profile
     *
     * @return Member
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
     * Set captain
     *
     * @param string $captain
     *
     * @return Member
     */
    public function setCaptain($isCaptain)
    {
        $this->isCaptain= $isCaptain;

        return $this;
    }

    /**
     * Get captain
     * @return string
     */
    public function getCaptain()
    {
        return $this->isCaptain;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Member
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