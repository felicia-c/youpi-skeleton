<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $introTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $introText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteContact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $theme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commercialBandTitle;
    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $commercialBandText;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commercialBandButtonText;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $quoteText;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quoteAuthor;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quoteAuthorStatus;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private $contactBlockText;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $headerBgImage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIntroTitle(): ?string
    {
        return $this->introTitle;
    }

    public function setIntroTitle(?string $introTitle): self
    {
        $this->introTitle = $introTitle;

        return $this;
    }

    public function getIntroText(): ?string
    {
        return $this->introText;
    }

    public function setIntroText(?string $introText): self
    {
        $this->introText = $introText;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSiteContact()
    {
        return $this->siteContact;
    }

    public function setSiteContact(?string $siteContact): self
    {
        $this->siteContact = $siteContact;

        return $this;
    }

    public function getPreTitle(): ?string
    {
        return $this->preTitle;
    }

    public function setPreTitle(?string $preTitle): self
    {
        $this->preTitle = $preTitle;

        return $this;
    }

    public function getMainTitle(): ?string
    {
        return $this->mainTitle;
    }

    public function setMainTitle(?string $mainTitle): self
    {
        $this->mainTitle = $mainTitle;

        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(?string $subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }
    public function getHeaderBgImage()
    {
        return $this->headerBgImage;
    }

    public function setHeaderBgImage($headerBgImage): self
    {
        $this->headerBgImage = $headerBgImage;

        return $this;
    }

    //commercial band
    public function getCommercialBandTitle(): ?string
    {
        return $this->commercialBandTitle;
    }

    public function setCommercialBandTitle(?string $commercialBandTitle): self
    {
        $this->commercialBandTitle = $commercialBandTitle;

        return $this;
    }
    public function getCommercialBandText(): ?string
    {
        return $this->commercialBandText;
    }

    public function setCommercialBandText(?string $commercialBandText): self
    {
        $this->commercialBandText = $commercialBandText;

        return $this;
    }
    public function getCommercialBandButtonText(): ?string
    {
        return $this->commercialBandButtonText;
    }

    public function setCommercialBandButtonText(?string $commercialBandButtonText): self
    {
        $this->commercialBandButtonText = $commercialBandButtonText;

        return $this;
    }

    public function getQuoteText(): ?string
    {
        return $this->quoteText;
    }

    public function setQuoteText(?string $quoteText): self
    {
        $this->quoteText = $quoteText;

        return $this;
    }
    public function getQuoteAuthor(): ?string
    {
        return $this->quoteAuthor;
    }

    public function setQuoteAuthor(?string $quoteAuthor): self
    {
        $this->quoteAuthor = $quoteAuthor;

        return $this;
    }
    public function getQuoteAuthorStatus(): ?string
    {
        return $this->quoteAuthorStatus;
    }

    public function setQuoteAuthorStatus(?string $quoteAuthorStatus): self
    {
        $this->quoteAuthorStatus = $quoteAuthorStatus;

        return $this;
    }

    public function getContactBlockText(): ?string
    {
        return $this->contactBlockText;
    }

    public function setContactBlockText(?string $contactBlockText): self
    {
        $this->contactBlockText = $contactBlockText;

        return $this;
    }
}
