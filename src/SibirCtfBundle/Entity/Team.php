<?php

namespace SibirCtfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SibirCtfBundle\Entity\Member;

/**
 * @ORM\Entity
 * @ORM\Table(name="team")
 */
class Team
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @var ArrayCollection|Member[]
     *
     * @ORM\OneToMany(targetEntity="Member", mappedBy="team", cascade={"persist"})
     */
    private $members;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $school;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @var Boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $conference;

    /**
     * @var Boolean
     * @ORM\Column(type="integer")
     */
    private $status = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status_description = "На рассмотрении";

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Team
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Team
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Team
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    public function addMembers(Member $member)
    {
        if (!$this->members->contains($member)) {
            $member->setTeam($this);
            $this->members->add($member);
        }

        return $this;
    }
    /**
     * Set members
     *
     * @param string $members
     *
     * @return Team
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     * @return ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set school
     *
     * @param string $school
     *
     * @return Team
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     * @return string
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Team
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Team
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set conference
     *
     * @param string $conference
     *
     * @return Team
     */
    public function setConference($conference)
    {
        $this->conference = $conference;

        return $this;
    }

    /**
     * Get conference
     * @return string
     */
    public function getConference()
    {
        return $this->conference;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Team
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get status_description
     * @return string
     */
    public function getStatusDescription()
    {
        return $this->status_description;
    }

    /**
     * Set status_description
     *
     * @param string $description
     *
     * @return Team
     */
    public function setStatusDescription($description)
    {
        $this->status_description = $description;

        return $this;
    }
}
