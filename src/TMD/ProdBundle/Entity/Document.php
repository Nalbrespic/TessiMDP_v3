<?php

namespace TMD\ProdBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="document")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // la propri??t?? ?? file ?? peut ??tre vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }

        if ($this->path != $this->file->getClientOriginalName()) {
            $this->path = $this->file->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload($idClient)
    {
        // la propri??t?? ?? file ?? peut ??tre vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }
        $file_name = $this->file->getClientOriginalName();
        $local_path = $this->path;

        if($idClient == 698) {
            $ftp_server = "sftp01.tessicustomermarketing.fr";
            $ftp_user_name = "marie-claire";
            $ftp_user_pass = "Pfj9n53lGZ";
        } elseif ($idClient == 709){
            $ftp_server = "sftp01.tessicustomermarketing.fr";
            $ftp_user_name = "ediis";
            $ftp_user_pass = "FV553HcPb1";
        }
            $conn_id = ssh2_connect($ftp_server, 22) or die("Erreur de connexion avec le serveur FTP");
            $login_result = ssh2_auth_password($conn_id, $ftp_user_name, $ftp_user_pass);

            if ((!$conn_id) || (!$login_result)) {  // check connection
                // wont ever hit this, b/c of the die call on ftp_login
                $errorMessage= "<span style='color:#FF0000'><h2>FTP connection has failed! <br />";
                $errorMessage += "Attempted to connect to $ftp_server for user $ftp_user_name</h2></span>";
                exit;
            } else {
                $message = "Connected to $ftp_server, for user $ftp_user_name <br />";
                //echo "Connected to $ftp_server, for user $ftp_user_name <br />";
                $sftp = ssh2_sftp($conn_id);

//                ssh2_scp_send($conn_id,$local_path,"/in/$file_name");

//                ssh2_sftp_mkdir($sftp,"DossierTest2");

                $stream = fopen("ssh2.sftp://".intval($sftp)."/in/$file_name","w");
                $file = file_get_contents($local_path);
                fwrite($stream, $file);
                fclose($stream);
//                dump($stream);
            }

//        $file_name = $this->file->getClientOriginalName();
//
//        // la m??thode ?? move ?? prend comme arguments le r??pertoire cible et
//        // le nom de fichier cible o?? le fichier doit ??tre d??plac??
//        if (!file_exists($this->getUploadRootDir())) {
//            mkdir($this->getUploadRootDir(), 0775, true);
//        }
//        $this->file->move(
//            $this->getUploadRootDir(), $file_name
//        );
        $this->file = null;
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Document
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
     * Set path
     *
     * @param string $path
     *
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}