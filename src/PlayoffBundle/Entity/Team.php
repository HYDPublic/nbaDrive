<?php

namespace PlayoffBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="pf_team")
 * @ORM\Entity(repositoryClass="PlayoffBundle\Repository\TeamRepository")
 */
class Team
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="aid", type="string", length=25)
     */
    private $aid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=100)
     */
    private $shortname;

    /**
     * @var string
     *
     * @ORM\Column(name="conference", type="string", length=50)
     */
    private $conference;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer", length=3)
     */
    private $rank;

    /**
    * @ORM\Column(name="logo", type="string")
    */
    private $logo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set aid
     *
     * @param string $aid
     *
     * @return Team
     */
    public function setAid($aid)
    {
        $this->aid = $aid;

        return $this;
    }

    /**
     * Get aid
     *
     * @return string
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortname
     *
     * @param string $shortname
     *
     * @return Team
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Get shortname
     *
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
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
     *
     * @return string
     */
    public function getConference()
    {
        return $this->conference;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return Team
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
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
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
