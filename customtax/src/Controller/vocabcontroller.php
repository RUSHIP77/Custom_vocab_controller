<?php

namespace Drupal\customtax\Controller;
use Drupal\Core\Controller\ControllerBase;


/**
 * Class vocabcontroller.
 *
 * @package Drupal\customtax\Controller
 */




class vocabcontroller extends ControllerBase
{

/**
     * Summary of display
     * @param mixed $listid
     * @param mixed $vocabid
     * @return void
     */




    // SORTING FUNCTION
    public function sortBy($arr, $key)
    {
        $key_arr = array_column($arr, $key);
        $_GET['sort'] == "asc" ? array_multisort($key_arr, SORT_ASC, $arr) : array_multisort($key_arr, SORT_DESC, $arr);
        return $arr;
    }   

    

    public function display($vocabid, $listid)
    {
       
        
    //Loads all taxonomy data and stores it into $termsdata variable
        $taxtermsdata = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadMultiple();


    //TABLE headers array is all heading titles of the resultant table that we are going to have.
        $table_headers = array(
            'tid' => 'Term ID',
            'name' => 'Term Name',
            'description' => ("Description"),
            'vocabid' => "Vocabulary Name",
        );
        

    //Function to print the description message if no error is found!
        function get_error() {
             return ['#type' => 'markup',    
                    '#markup' => t('No records found'),
                ];
            }

          

        
         //Condition to search for specific termid inside a specific voacbulary
        if ($listid != NULL && $vocabid != NULL) {

            foreach ($taxtermsdata as $term) {
                if ($term->vid->target_id == $vocabid && $term->tid->value == $listid) {
                    $term_res[] = array(
                        'tid' => $term->tid->value,
                        'name' => $term->name->value,
                        'description' => $term->description->processed == NULL ? 'NO Description' : $term->description->processed,
                        'vocabid' => $term->vid->target_id,
                    );
                }
            }

            $table_headers;
            
            if (!$term_res) {
                return get_error();
              }

        } 





       // Search for a specific vocabulary group!  
        else if ($vocabid != NULL) {
            foreach ($taxtermsdata as $term) {
                if ($term->vid->target_id != $vocabid) continue;
                $term_res[] = array(
                    'tid' => $term->tid->value,
                    'name' => $term->name->value,
                    'description' => $term->description->processed == NULL ? 'No description' : $term->description->processed,
                    'vocabid' => $term->vid->target_id,
                );
            }
                
            //Sorting condition
            if ($_GET['sort'] != NULL) $term_res = $this->sortBy($term_res, 'name');

            $table_headers ;
             
            if (!$term_res) {
                return get_error();
              }

        }





        // shows all the vocabulary data
        else {
            foreach ($taxtermsdata as $term) {

                $term_res[] = array(
                    'tid' => $term->tid->value,
                    'name' => $term->name->value,
                    'description' => $term->description->processed == NULL ? 'No description' : $term->description->processed,
                    'vocabid' => $term->vid->target_id,
                );
            }

            if ($_GET['sort'] != NULL) $term_res = $this->sortBy($term_res, 'name');

            $table_headers ;
         
            if (!$term_res) {
                return get_error();
              }

            }  




       //This is the table that is finally displayed as ouput in drupal website!
        $res['table'] = [
            '#type' => 'table',
            '#title' => 'Taxonomy',
            '#header' => $table_headers,
            '#rows' => $term_res,
        ];

        return $res;
        
             
    }
    
}























//             $columns = array_column($term_res, 'name');
// array_multisort($columns, SORT_ASC, $term_res);
// public function sortByOrder($a, $b) {
    //     if ($a['name'] > $b['name']) {
    //         return 1;
    //     } elseif ($a['name'] < $b['name']) {
    //         return -1;
    //     }
    //     return 0;
    // }

      // sorting data by name



            //TRYING TO SORT BY NAME IN ASCENDING ORDER
            
                // usort($term_res, 'sortByOrder');