<?php

namespace Drupal\serafmod\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

class SerafController extends ControllerBase
{
    public function content()
    {
        // gets data from inputs
        $title = \Drupal::request()->query->get('job-name') ?? null;
        $location = \Drupal::request()->query->get('location') ?? null;
        $checkbox = \Drupal::request()->query->get('full-time-only') ?? null;
        //query drupal entities according to provided logic
        if (empty($title) && empty($location)) {
            $query = \Drupal::entityQuery('node')
                ->condition('type', 'job');
            $nids = $query->execute();
        } elseif (!empty($title) && !empty($location)) {
            $query = \Drupal::entityQuery('node')
                ->condition('type', 'job')
                ->condition('field_job_name', $$title, 'CONTAINS')
                ->condition('field_location', $location, 'CONTAINS');
            $nids = $query->execute();
        } elseif (!empty($location) ){
            $query = \Drupal::entityQuery('node')
            ->condition('type', 'job')
            ->condition('field_location', $location, 'CONTAINS');
            $nids = $query->execute();
        } elseif (!empty($title) ){
            $query = \Drupal::entityQuery('node')
            ->condition('type', 'job')
            ->condition('field_job_name', $title, 'CONTAINS');
            $nids = $query->execute();
        }
        if (empty($title) && empty($location) && !empty($checkbox)){
            $query = \Drupal::entityQuery('node')
                ->condition('type', 'job')
                ->condition('field_job_type', $checkbox, 'CONTAINS');
            $nids = $query->execute();
        }
       
        $jobs = [];
        
        foreach ($nids as $i => $nid) {
            $node = Node::load($nid);
            $fid = $node->field_company_logo->getValue()[0]['target_id'] ?? null;
            $file = File::load($fid);
            $image_uri = $file->getFileUri();
            $style = ImageStyle::load('thumbnail');
            $uri = $style->buildUri($image_uri);
            $url = $style->buildUrl($image_uri);
            //push data in to jobs array
            $jobs[$nid] = [
                'id' => $i,
                'logo' => $url,
                'company_name' => $node->field_company_name->getValue()[0]['value'],
                'date' => $node->field_job_date->getValue()[0]['value'],
                'job_type' => $node->field_job_type->getValue()[0]['value'],
                'job_name' => $node->field_job_name->getvalue()[0]['value'],
                'location' => $node->field_location->getValue()[0]['value']
            ];
        }
        //returns custom theme for module and jobs array
        return  [
            '#theme' => 'jobs_item',
            '#jobs' => $jobs
        ];
    }
}
