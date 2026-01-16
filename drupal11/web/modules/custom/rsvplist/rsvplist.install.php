<?php

/**
 * @file
 * install, update & uninstall for rsvp module.
 */

function rsvplist_schema(){
//create a db table called 'rsvplist'
  //1.id[serial], 2.uid[int], 3.nid[int], 4.mail[varchar], 5.created[int]
  $schema['rsvplist'] = [
    'description' => 'stores email, timestamp, nid&uid for an RSVP',
    'fields'=>[
      'id'=>[
        'description'=>'the primary identifier for tje record',
        'type'=>'serial',
        'size'=>'small',//tiny/small/medium/normal/big
        'unsigned'=>TRUE,
        'not null'=>TRUE,
      ],
      'uid'=>[
        'description'=>'The {users}.uid that added this RSVP',
        'type'=>'int',
        'not null'=>'TRUE',
        'defaults'=>0,
      ],
      'nid'=>[
        'description'=>'The {node}.nid for this RSVP',
        'type'=>'int',
        'unsigned'=>TRUE,
        'not null'=>TRUE,
        'defaults'=>0,
      ],
      'mail'=>[
        'description'=>'User\'s email address',
        'type'=>'varchar',
        'length'=>64,
        'not null'=>FALSE,
        'defaults'=>'',
      ],
      'created'=>[
        'description'=>'Timestamp',
        'type'=>'int',
        'not null'=>TRUE,
        'defaults'=>0,
      ],
    ],
    'primary key'=>['id'],
    'indexes'=>[
      'node'=>['nid'],
      'node_user'=>['nid', 'uid'],
    ],
  ];

  //create a db table named 'rsvplist_enabled
  //1.nid[int]
  $schema['rsvplist_enabled'] = [
    'description'=>'Tracks whether RSVP is enabled or not for a node',
    'fields'=>[
      'nid'=>[
        'description'=>'The {node}.nid that has RSVPList enabled',
        'type'=>'int',
        'unsigned'=>TRUE,
        'not null'=>TRUE,
        'defaults'=>0,
      ],
    ],
  ];
  return $schema;
}
