<?php

return [
    'permissions' => [
        [
            'menu'  => 'Settings',
            'items' => [
                [
                    'name'       => 'Account Configuration',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Applicant Source',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Applicant Status',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Applicant Trashbox',
                    'read'       => true,
                    'write'      => false,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Clinic Manager',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Document Type',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Email Template',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Role Manager',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'User Manager',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ]
            ]
        ],
        [
            'menu'  => 'Employers',
            'items'  => [
                [
                    'name'       => 'Manpower Request',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'My Manpower Request',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Employer Manager',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ]
            ]
        ],
        [
            'menu'  => 'Applicants',
            'items'  => [
                [
                    'name'       => 'Add Applicant',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Applicant Pipeline',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Interview Schedule',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Quick Encode',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Search Applicant',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Source Applicant',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ]
            ]
        ],
        [
            'menu'  => 'Processing',
            'items'  => [
                [
                    'name'       => 'Applicant Monitoring',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Document Monitoring',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Document List',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Medical',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ]
            ]
        ],
        [
            'menu'  => 'Web Management',
            'items'  => [
                [
                    'name'       => 'Job Openings',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Announcements',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Online Applicant',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Unposted Job',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => true,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ]
            ]
        ],
        [
            'menu'  => 'Reports',
            'items'  => [
                [
                    'name'       => 'Applicants Encoded',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Applicants Source',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Audit Trail',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Birthday',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Contract Expiration',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Deployment Report',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Interview Calendar',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Manpower Request',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ],
                [
                    'name'       => 'Status Report',
                    'read'       => true,
                    'write'      => true,
                    'delete'     => false,
                    'can_read'   => false,
                    'can_write'  => false,
                    'can_delete' => false
                ]
            ]
        ]
    ],
    'educational_levels' => [
        [
            'id'   => 1,
            'name' => 'Any'
        ],
        [
            'id'   => 2,
            'name' => 'Primary'
        ],
        [
            'id'   => 3,
            'name' => 'High School Diploma'
        ],
        [
            'id'   => 4,
            'name' => 'Vocational Diploma / Short Course Certificate'
        ],
        [
            'id'   => 5,
            'name' => 'College Level'
        ],
        [
            'id'   => 6,
            'name' => 'Bachelor\'s / College Degree'
        ],
        [
            'id'   => 7,
            'name' => 'Post Graduate Diploma / Master\'s Degree'
        ],
        [
            'id'   => 8,
            'name' => 'Prof\'l License(Passed Board/Bar/Prof\'l License Exam)'
        ],
        [
            'id'   => 9,
            'name' => 'Doctorate Degree'
        ]
    ],
    'skill_levels' => [
        [
            'id'   => 1,
            'name' => 'Beginner'
        ],
        [
            'id'   => 2,
            'name' => 'Fair'
        ],
        [
            'id'   => 3,
            'name' => 'Average'
        ],
        [
            'id'   => 4,
            'name' => 'Advanced'
        ],
        [
            'id'   => 5,
            'name' => 'Expert'
        ]
    ]
];