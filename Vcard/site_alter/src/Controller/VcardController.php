<?php

namespace Drupal\site_alter\Controller;

use Drupal\node\NodeInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\redirect\Entity\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\taxonomy\Entity\Term;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;




/**
 * Controller routines for AJAX routes.
 */
class VcardController extends ControllerBase {

  public function vcardFetch(NodeInterface $node) {

    if($node){
        $name = $node->get('title')->getValue();
        $email = $node->get('field_email')->getValue();
        $image_file_id = $node->get('field_team_image')->getValue();
        $telephone = $node->get('field_telephone')->getValue();
        $nid = $node->get('nid')->getValue();
        $filename = $nid[0]['value'].".vcf";
        $social = $node->get('field_linkedin')->getString();
        $designation_id = $node->get('field_designation')->getString();
        
        // Trim Social field
        $social_link=trim($social);
        $social_arr = explode (",", $social_link);  

        // Get designation from tid
        $term = Term::load($designation_id);
        $designation = $term->getName();

        // Get Image url
        $media = Media::load($image_file_id[0]['target_id']);
        if($media){
          $fid = $media->field_media_image->target_id;
          $file = File::load($fid);
          $image_url = $file->url();
        }

      	$data=null;
        $data.="BEGIN:VCARD\n";
        $data.="VERSION:3.0\n";
        $data.="FN:".$name[0]['value']."\n";
        $data.="N:".$name[0]['value']."\n";
        $data.="ORG:" . "Stellus Capital" . "\n";
        $data.="TITLE:".$designation."\n";    
        $data.="EMAIL;TYPE=work:" .$email[0]['value']."\n";
        $data.="PHOTO;JPEG;ENCODING=BASE64:".base64_encode(file_get_contents($image_url))."\n";    
        $data.="URL;type=pref:https://www.linkedin.com/".$social_arr[1]."\n";
        $data.="END:VCARD";

       	$path = base_path();
       	$fullpath =  $_SERVER['DOCUMENT_ROOT'].''.base_path();
       	$filePath = $fullpath."sites/default/files/vcard/".$filename;
        $redirection_path = base_path().'sites/default/files/vcard/'.$filename;

       	$file = fopen($filePath,"w");
        fwrite($file,$data);
       	fclose($file);   
       	 
        return new RedirectResponse($redirection_path);     
    }
  }
}
