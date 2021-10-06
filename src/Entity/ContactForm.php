<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ContactFormRepository;

/**
 * @ORM\Entity(repositoryClass=ContactFormRepository::class)
 */
class ContactForm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le champ 'Nom' doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Le champ 'Nom' ne doit pas dépasser {{ limit }} caractères.",
     *      allowEmptyString = false
     * )
     * @var string
     * @Assert\NotBlank(message="Le champ 'Nom' ne peut pas être vide.")
     * @Assert\Type("string")
     * @Assert\Regex(
     *     pattern="#^[a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ. '-]+$#",
     *     match=true,
     *     message="Le champ 'Nom' contient un ou plusieurs caractères non autorisés."
     * )
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 3,
     *     max = 70,
     *     minMessage="Le champ 'Email' doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Le champ 'Email' ne doit pas dépasser {{ limit }} caractères.",
     *      allowEmptyString = false
     * )
     * @var string
     * @Assert\NotBlank(message="Le champ 'Email' ne peut pas être vide.")
     * @Assert\Email(
     *     mode="strict",
     *     message = "L'adresse email que vous avez renseigner n'est pas valide."
     * )
     */
    protected $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "Le champ 'Adresse' doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Le champ 'Adresse' ne doit pas dépasser {{ limit }} caractères.",
     * )
     * @var string
     * @Assert\Type("string")
     * @Assert\Regex(
     *     pattern="#^[0-9a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ. '-]+$#",
     *     match=true,
     *     message="Le champ 'Adresse' contient un ou plusieurs caractères non autorisés."
     * )
     */
    protected $adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 60,
     *      minMessage = "Le champ 'Ville' doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Le champ 'Ville' ne doit pas dépasser {{ limit }} caractères.",
     *      allowEmptyString = false
     * )
     * @var string
     * @Assert\Type("string")
     * @Assert\Regex(
     *     pattern="#^[a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ '-]+$#",
     *     match=true,
     *     message="Le champ 'Ville' contient un ou plusieurs caractères non autorisés."
     * )
     */
    protected $city;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 5,
     *      maxMessage = "Le champ 'Code Postal' ne doit pas dépassé {{ limit }} caractères",
     *      allowEmptyString = true
     * )
     * @Assert\Type("string")
     * @var string
     * @Assert\Regex(
     *     pattern="#^[0-9a-zA-Z]+$#",
     *     match=true,
     *     message="Le champ 'Code Postal' contient un ou plusieurs caractères non autorisés."
     * )
     */
    protected $cp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Le numero de téléphone n'a pas une longueur valide.",
     *      allowEmptyString = true
     * )
     * @var string
     * @Assert\Type("string")
     * @Assert\Regex(
     *     pattern="#^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$#",
     *     match=true,
     *     message="Le champ 'Téléphone' n'a pas le format valide."
     * )
     */
    protected $telnumber;

    /**
     *
     * @ORM\Column(type="text", length=4999, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 5,
     *      max = 5000,
     *      minMessage = "Votre message doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Votre message ne doit pas dépassé {{ limit }} caractères.",
     *      allowEmptyString = true
     * )
     * @var string
     * @Assert\NotBlank(message="Le champ 'Message' ne peut pas être vide.")
     * @Assert\Regex(
     *     pattern="#[<>\$=]#",
     *     match=false,
     *     message="Le champ 'Message' contient un ou plusieurs caractères non autorisés."
     * )
     */
    protected $message;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    protected $send;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAdress(): string
    {
        return $this->adress;
    }

    /**
     * @param string $adress
     */
    public function setAdress(string $adress): void
    {
        $this->adress = $adress;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getCp(): string
    {
        return $this->cp;
    }

    /**
     * @param string $cp
     */
    public function setCp(string $cp): void
    {
        $this->cp = $cp;
    }

    /**
     * @return string
     */
    public function getTelnumber(): string
    {
        return $this->telnumber;
    }

    /**
     * @param string $telnumber
     */
    public function setTelnumber(string $telnumber): void
    {
        $this->telnumber = $telnumber;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param mixed $send
     */
    public function setSend($send): void
    {
        $this->send = $send;
    }



}