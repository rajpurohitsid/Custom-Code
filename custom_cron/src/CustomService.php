<?php


namespace  Drupal\custom_cron;

use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;
use \Drupal\taxonomy\Entity\Term;
use \Drupal\Core\Database\Database;

class CustomService {

	public function createNode() {

	$data = file_get_contents('http://delivery.chalk247.com/team_list/NFL.JSON?api_key=74db8efa2a6db279393b433d97c2bc843f8e32b0');
	$team_list = json_decode($data, TRUE);
  	
  	foreach ($team_list as $team_data) {
  		foreach ($team_data as $value) {
  			foreach ($value as $data) {
  				foreach ($data as $team) {

  					//Getting All Fields value.
  					$name = $team['name'];
  					$nickname = $team['nickname'];
  					$display_name = $team['display_name'];
  					$team_id = $team['id'];
  					$conference = $team['conference'];
  					$division = $team['division'];


  					//Check if Parent Term Exist or Not Starts
  					$properties['name'] = $conference;
					$parentterms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties($properties);
					$parentterm = reset($parentterms);
  					
  					if(empty($parentterm)){

	  					//Create Parent if Not Exist
	  					$parent = Term::create([
						  'vid' => 'conference',
						  'name' => $conference, 
						  'parent' => 0,
						]);
						$parent->save();
						if(!empty($parent)){
							$parent_id = $parent->id();
						}
  					}else{
  						if(!empty($parentterm)){
  							$parent_id = $parentterm->id();
  						}
  					}
  					//Check if Parent Term Exist or Not Ends


  					//Check if Child Term Exist or Not Starts
  					$properties['name'] = $division;
					$childterms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties($properties);
					$childterm = reset($childterms);
						
						if (empty($childterm)){
						//Create Child From Division Field
	  					$child = Term::create([
						  'vid' => 'conference',
						  'name' => $division, 
						  'parent' => ['target_id' => $parent_id],
						]);
						$child->save();
						
					}

					//Load Tree and Check if Division value Exist or not in Parent term . if Not Exist then create New
					$tree = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('conference', $parent_id, 1, TRUE);
					$result = [];
					foreach ($tree as $term) {
						$result[] = $term->get('name')->value;

					}
					
					if (!in_array($division,$result)){

						//Create Child From Division Field
	  					$child = Term::create([
						  'vid' => 'conference',
						  'name' => $division, 
						  'parent' => ['target_id' => $parent_id],
						]);
						$child->save();
						
					}

  	
					//Check and Update Node if Already Exist.
	  				$query = \Drupal::entityQuery('node')
					    ->condition('status', 1)
					    ->condition('field_id', $team_id);
					$nids = $query->execute();
					
					if($nids){
						$node = Node::load($nids);
						if($node){
						$node->set('title',$name);
						$node->set('field_name',$name);
						$node->set('field_nickname',$nickname);
						$node->set('field_display_name',$display_name);
						$node->set('field_id',$team_id);
						$node->set('field_conference',['target_id' => $parent_id]);
						$node->set('field_division',$division);

						$node->save();
						}
					}else{
						//Create New Node.
	  					$node = Node::create(['type' => 'team']);
					    $node->langcode = "en";
					    $node->title= $name;
					    $node->field_name = $name;
					    $node->field_nickname = $nickname;
					    $node->field_display_name = $display_name;
					    $node->field_id = $team_id;
					    $node->field_conference  = [
					      ['target_id' => $parent_id]
					    ];
					    $node->field_division = $division;

						$node->save();
					}
  				}
  			}
  		}
	}
	}
}

