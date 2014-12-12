<?php
/**
*	events class: events CRUD
*				  image gallery
*/

class events
{

    protected $db;

    /**
     * @param $database
     */
    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * @param $id
     * check if event exists
     */
    public function event_exists($id)
    {
        $query = $this->db->prepare("SELECT `id` FROM `events` WHERE `id` = :id LIMIT 1");
        $query->bindParam(":id", $id, PDO::PARAM_INT);

        try {
            $query->execute();
            if ($query->rowCount() == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_name
     * check if event exists
     */
    public function event_exists_gallery($event_name)
    {
        $query = $this->db->prepare("SELECT `id` FROM `events` WHERE `name` = :name LIMIT 1");
        $query->bindParam(":name", $event_name, PDO::PARAM_STR);

        try {
            $query->execute();
            if ($query->rowCount() == 1) {
                return $query->fetchAll(); //return event ID
            } else {
                return false;
            }

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $id
     * check if image exists in event gallery
     */
    public function imageGallery_exists($id)
    {
        $query = $this->db->prepare("SELECT `id` FROM `events_gallery` WHERE `id` = :id LIMIT 1");
        $query->bindParam(":id", $id, PDO::PARAM_INT);

        try {
            $query->execute();
            if ($query->rowCount() == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param void
     * get all events names
     */
    public function getEventsNames()
    {
        $query = $this->db->prepare("SELECT `name` FROM `events`", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $eventNames = array();

        try {
            $query->execute();

            //store all names to array and return them
            for ($i = 0; $row = $query->fetch(); $i++) {
                $eventNames[] = $row['name'];
            }

            return $eventNames;

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param void
     * check events number stats
     */
    public function event_count()
    {
        $num_stats = array();
        $num_all = $num_active = 0;

        $query_all = $this->db->prepare("SELECT COUNT(`id`) FROM `events`");

        $status = "yes";
        $query_active = $this->db->prepare("SELECT COUNT(`id`) FROM `events` WHERE `active` = :active");
        $query_active->bindParam(":active", $status, PDO::PARAM_STR);

        try {
            //count and store to variables
            $query_all->execute();
            $num_all = $query_all->fetch(PDO::FETCH_NUM);

            $query_active->execute();
            $num_active = $query_active->fetch(PDO::FETCH_NUM);

            //store to array and return
            $num_stats[0] = $num_all;
            $num_stats[1] = $num_active;
            return $num_stats;

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_name
     * @param $event_description
     * @param $event_description_en
     * @param $event_active
     * @param $image_info
     * add new event
     */
    public function add($event_name, $event_description, $event_description_en, $event_active, $image_info)
    {
        $event_name = htmlspecialchars($event_name, ENT_NOQUOTES, "UTF-8");
        $event_description = htmlspecialchars($event_description, ENT_NOQUOTES, "UTF-8");
        $event_description_en = htmlspecialchars($event_description_en, ENT_NOQUOTES, "UTF-8");
        $event_active = htmlspecialchars($event_active, ENT_NOQUOTES, "UTF-8");
        $added = time();

        //image vars from array (name, path, size)
        $image_name = htmlspecialchars($image_info[0], ENT_NOQUOTES, "UTF-8");
        $image_path = $image_info[1];
        $image_size = $image_info[2];

        //insert event to db
        $query = $this->db->prepare("INSERT INTO `events` (`name`, `description`, `description_en`, `active`, `added`) VALUES (:name, :description, :description_en, :active, :added)");
        $query->bindParam(":name", $event_name, PDO::PARAM_STR);
        $query->bindParam(":description", $event_description, PDO::PARAM_STR);
        $query->bindParam(":description_en", $event_description_en, PDO::PARAM_STR);
        $query->bindParam(":active", $event_active, PDO::PARAM_STR);
        $query->bindParam(":added", $added, PDO::PARAM_STR);

        try {
            $query->execute();
            $get_event_id = $this->db->lastInsertId(); //event_id for cover table

            //insert cover into db
            $query_cover = $this->db->prepare("INSERT INTO `events_cover` (`event_id`, `file_name`, `file_location`, `file_size`) VALUES (:event_id, :file_name, :file_location, :file_size)");
            $query_cover->bindParam(":event_id", $get_event_id, PDO::PARAM_INT);
            $query_cover->bindParam(":file_name", $image_name, PDO::PARAM_STR);
            $query_cover->bindParam(":file_location", $image_path, PDO::PARAM_STR);
            $query_cover->bindParam(":file_size", $image_size, PDO::PARAM_STR);

            try {
                $query_cover->execute();
                return true;

            } catch (PDOException $ex) {
                die($ex->getMessage());
            }
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_id
     * @param $event_name
     * @param $event_description
     * @param $event_description_en
     * @param $event_active
     * @param $image_info
     * edit event
     */
    public function edit($event_id, $event_name, $event_description, $event_description_en, $event_active, $image_info)
    {
        $event_name = htmlspecialchars($event_name, ENT_NOQUOTES, "UTF-8");
        $event_description = htmlspecialchars($event_description, ENT_NOQUOTES, "UTF-8");
        $event_description_en = htmlspecialchars($event_description_en, ENT_NOQUOTES, "UTF-8");
        $event_active = htmlspecialchars($event_active, ENT_NOQUOTES, "UTF-8");

        //image vars from array (name, path, size)
        $image_name = $image_info[0];
        $image_path = $image_info[1];
        $image_size = $image_info[2];

        //update events table
        $query = $this->db->prepare("UPDATE `events` SET `name` = :name, `description` = :description, `description_en` = :description_en, `active` = :active WHERE `id` = :id");
        $query->bindParam(":name", $event_name, PDO::PARAM_STR);
        $query->bindParam(":description", $event_description, PDO::PARAM_STR);
        $query->bindParam(":description_en", $event_description_en, PDO::PARAM_STR);
        $query->bindParam(":active", $event_active, PDO::PARAM_STR);
        $query->bindParam(":id", $event_id, PDO::PARAM_INT);

        try {
            $query->execute();

            //update events_cover table
            $query_cover = $this->db->prepare("UPDATE `events_cover` SET `file_name` = :file_name, `file_location` = :file_location, `file_size` = :file_size WHERE `event_id` = :event_id");
            $query_cover->bindParam(":file_name", $image_name, PDO::PARAM_STR);
            $query_cover->bindParam(":file_location", $image_path, PDO::PARAM_STR);
            $query_cover->bindParam(":file_size", $image_size, PDO::PARAM_STR);
            $query_cover->bindParam(":event_id", $event_id, PDO::PARAM_INT);

            try {
                $query_cover->execute();
                return true;

            } catch (PDOException $ex) {
                die($ex->getMessage());
            }

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_id
     * @param $event_name
     * @param $old_event_name
     * @param $galleryImages
     * rename gallery directory and all images if exist
     */
    public function editGallery($event_id, $event_name, $old_event_name, $galleryImages)
    {
        $event_name = safe_name($event_name);
        $gallery_path = "events_gallery/".safe_name($event_name)."/";
        $unique_arrays = 0;

        $query = $this->db->prepare("UPDATE `events_gallery` SET `file_location` = :file_location WHERE `event_id` = :event_id");
        $query->bindParam(":file_location", $gallery_path, PDO::PARAM_STR);
        $query->bindParam(":event_id", $event_id, PDO::PARAM_INT);

        try{
            $query->execute();

            //rename images in DB
            if(!empty($galleryImages))
            {
                $unique_arrays = count(array_unique($galleryImages, SORT_REGULAR));

                for($i = 0; $i < $unique_arrays; $i++)
                {
                    $new_image_name = "";

                    $img_name_string = $galleryImages[$i]['file_name'];
                    $img_name_pattern = '/.*?(_[a-zA-Z-0-9]{10}\..+)/i';
                    $img_name_replacement = $event_name.'$1';
                    $new_image_name = preg_replace($img_name_pattern, $img_name_replacement, $img_name_string);

                    $query_img = $this->db->prepare("UPDATE `events_gallery` SET `file_name` = :file_name WHERE `id` = :id");
                    $query_img->bindParam(":file_name", $new_image_name, PDO::PARAM_STR);
                    $query_img->bindParam(":id", $galleryImages[$i]['id'], PDO::PARAM_INT);

                    $query_img->execute();
                }
            }

            //check if there is any gallery edit
            if($query->rowCount() > 0)
            {
                $directory = "../events_gallery/".safe_name($old_event_name)."/";
                $new_directory = "../events_gallery/".safe_name($event_name)."/";

                //rename directory if exists and all files inside
                if(file_exists($directory))
                {
                    //rename all files
                    if ($handle = opendir($directory)) {
                        while (false !== ($fileName = readdir($handle))) {
                            $name_string = $fileName;
                            $name_pattern = '/.*?(_[a-zA-Z-0-9]{10}\..+)/i';
                            $name_replacement = $event_name.'$1';
                            $newName = preg_replace($name_pattern, $name_replacement, $name_string);
                            @rename($directory.$fileName, $directory.$newName); //returns error code(5) on windows server but still works
                        }
                        closedir($handle);
                    }

                    //rename directory
                    rename($directory, $new_directory);
                }

                return true;
            }
            else
            {
                return true;
            }

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param void
     * fetch all events and their covers for admin page
     */
    public function getAllEventsAdmin()
    {
        $query = $this->db->prepare("SELECT `events`.*, `events_cover`.`event_id`, `events_cover`.`file_name`, `events_cover`.`file_location` FROM `events` INNER JOIN `events_cover` ON `events`.`id` = `events_cover`.`event_id` ORDER BY `events`.`id` DESC", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $eventsData = array();

        try {
            $query->execute();

            for ($i = 0; $row = $query->fetch(); $i++) {
                $eventsData[] = $row;
            }

            return $eventsData; //TODO: send it as JSON one day?


        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }


    /**
     * @param void
     * fetch all events and their covers for public page - LIMIT 8 events
     */
    public function getLastEventsPublic()
    {
        $query = $this->db->prepare("SELECT `events`.*, `events_cover`.`event_id`, `events_cover`.`file_name`, `events_cover`.`file_location` FROM `events` INNER JOIN `events_cover` ON `events`.`id` = `events_cover`.`event_id` WHERE events.`active` = 'yes' ORDER BY `events`.`id` DESC LIMIT 20", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $eventsData = array();

        try {
            $query->execute();

            for ($i = 0; $row = $query->fetch(); $i++) {
                $eventsData[] = $row;
            }

            return $eventsData; //TODO: send it as JSON one day?


        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $id
     * fetch all data for an event and his cover
     */
    public function getEvent($id)
    {
        $query = $this->db->prepare("SELECT `events`.*, `events_cover`.`event_id`, `events_cover`.`file_name`, `events_cover`.`file_location`, `events_cover`.`file_size` FROM `events` INNER JOIN `events_cover` ON `events`.`id` = `events_cover`.`event_id` WHERE `events`.`id` = :id");
        $query->bindparam("id", $id, PDO::PARAM_INT);

        $eventData = array();

        try{
            $query->execute();

            if($query->rowCount() == 1)
            {
                return $eventData[] = $query->fetch();
            }
            else
            {
                return false;
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $image_id
     * get image data from events gallery
     */
    public function getGalleryImage($image_id)
    {
        $query = $this->db->prepare("SELECT * FROM `events_gallery` WHERE `id` = :id");
        $query->bindparam("id", $image_id, PDO::PARAM_INT);

        $imageData = array();

        try{
            $query->execute();

            if($query->rowCount() == 1)
            {
                return $imageData[] = $query->fetch();
            }
            else
            {
                return false;
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_id
     * get ID and names of all gallery images, if any exists for certain event
     */
    public function getGalleryImageNames($event_id)
    {
        $query = $this->db->prepare("SELECT `id`, `file_name` FROM `events_gallery` WHERE `event_id` = :event_id");
        $query->bindParam("event_id", $event_id, PDO::PARAM_INT);

        try{
            $query->execute();

            if($query->rowCount() > 0)
            {
                return $query->fetchAll();
            }
            else
            {
                return false;
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_id
     * fetch all images in a gallery for an event
     */
    public function getEventGallery($event_id)
    {
        $query = $this->db->prepare("SELECT `id`, `file_name`, `file_location` FROM `events_gallery` WHERE `event_id` = :event_id");
        $query->bindParam("event_id", $event_id, PDO::PARAM_INT);

        try{
            $query->execute();

            if($query->rowCount() > 0)
            {
                return $query->fetchAll();
            }
            else
            {
                return false;
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_id
     * @param $image_info
     * add image to event gallery
     */
    public function fillEventGallery($event_id, $image_info)
    {
        //image vars from array (name, path, size)
        $image_name = htmlspecialchars($image_info[0], ENT_NOQUOTES, "UTF-8");
        $image_location = $image_info[1];
        $image_size = $image_info[2];

        $query = $this->db->prepare("INSERT INTO `events_gallery` (`event_id`, `file_name`, `file_location`, `file_size`) VALUES(:event_id, :file_name, :file_location, :file_size)");
        $query->bindParam("event_id", $event_id, PDO::PARAM_INT);
        $query->bindParam("file_name", $image_name, PDO::PARAM_STR);
        $query->bindParam("file_location", $image_location, PDO::PARAM_STR);
        $query->bindParam("file_size", $image_size, PDO::PARAM_STR);

        try{
            $query->execute();
            return true;

        } catch(PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $image_info
     * add cover photos
     */
    public function fillBarCovers($image_info)
    {
        //image vars from array (name, path, size)
        $image_name = htmlspecialchars($image_info[0], ENT_NOQUOTES, "UTF-8");
        $image_location = $image_info[1];
        $image_size = $image_info[2];

        $query = $this->db->prepare("INSERT INTO `cover_photos` (`file_name`, `file_location`, `file_size`) VALUES(:file_name, :file_location, :file_size)");
        $query->bindParam("file_name", $image_name, PDO::PARAM_STR);
        $query->bindParam("file_location", $image_location, PDO::PARAM_STR);
        $query->bindParam("file_size", $image_size, PDO::PARAM_STR);

        try{
            $query->execute();
            return true;

        } catch(PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $id
     * check if cover exists
     */
    public function imageCover_exists($id)
    {
        $query = $this->db->prepare("SELECT * FROM `cover_photos` WHERE `id` = :id LIMIT 1");
        $query->bindParam(":id", $id, PDO::PARAM_INT);

        try {
            $query->execute();
            if ($query->rowCount() == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * @param $image_id
     * get image data from covers
     */
    public function getCoverImage($image_id)
    {
        $query = $this->db->prepare("SELECT * FROM `cover_photos` WHERE `id` = :id");
        $query->bindparam("id", $image_id, PDO::PARAM_INT);

        $imageData = array();

        try{
            $query->execute();

            if($query->rowCount() == 1)
            {
                return $imageData[] = $query->fetch();
            }
            else
            {
                return false;
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $image_id
     * @param $imageData
     * delete the cover
     */
    public function coverDelete($image_id, $imageData)
    {
        $query = $this->db->prepare("DELETE FROM `cover_photos` WHERE `id` = :id");
        $query->bindParam("id", $image_id, PDO::PARAM_INT);

        try{
            $query->execute();

            //delete image from drive
            unlink("../../".$imageData['file_location'].$imageData['file_name']);
            return true;

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param void
     * fetch all cover photos
     */
    public function getBarCovers()
    {
        $query = $this->db->prepare("SELECT `id`, `file_name`, `file_location` FROM `cover_photos` ORDER BY `id` ASC");

        try{
            $query->execute();

            if($query->rowCount() > 0)
            {
                return $query->fetchAll();
            }
            else
            {
                return false;
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_id
     * @param $event_name
     * @param $event_cover
     * delete event and all images from it's gallery
     */
    public function delete($event_id, $event_name, $event_cover)
    {
            //delete event cover
        $query = $this->db->prepare("DELETE FROM `events_cover` WHERE `event_id` = :event_id");
        $query->bindParam("event_id", $event_id, PDO::PARAM_INT);

            //delete event cover
        unlink("../events/".$event_cover);

        try{
            $query->execute();

                //delete event gallery
            $query2 = $this->db->prepare("DELETE FROM `events_gallery` WHERE `event_id` = :event_id");
            $query2->bindParam("event_id", $event_id, PDO::PARAM_INT);

                //delete all images on drive
            rrmdir("../events_gallery/".$event_name);

            try{
                $query2->execute();

                    //delete event itself
                $query3 = $this->db->prepare("DELETE FROM `events` WHERE `id` = :id");
                $query3->bindParam("id", $event_id, PDO::PARAM_INT);

                try{
                    $query3->execute();
                    return true;

                }catch(PDOException $ex){
                    die($ex->getMessage());
                }

            }catch(PDOException $ex){
                die($ex->getMessage());
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $image_id
     * @param $imageData
     * delete an image from event gallery
     */
    public function imageDelete($image_id, $imageData)
    {
        $query = $this->db->prepare("DELETE FROM `events_gallery` WHERE `id` = :id");
        $query->bindParam("id", $image_id, PDO::PARAM_INT);

        try{
            $query->execute();

            //delete image from drive
            unlink("../".$imageData['file_location'].$imageData['file_name']);
            return true;

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $event_id
     * return integer representing number of images in event gallery
     */
    public function eventImageGalleryCounter($event_id)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM `events_gallery` WHERE `event_id` = :event_id");
        $query->bindParam("event_id", $event_id, PDO::PARAM_INT);

        try{
            $query->execute();
            $imageCount = $query->fetch(PDO::FETCH_NUM);

            return $imageCount[0];

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

	
}

?>