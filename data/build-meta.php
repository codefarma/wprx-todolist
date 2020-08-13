<?php
return <<<'JSON'
{
    "framework_version": "2.2.7",
    "ms_tables": [
        {
            "name": "todo_lists",
            "columns": {
                "id": {
                    "allow_null": false,
                    "auto_increment": true,
                    "binary": false,
                    "comment": "",
                    "decimals": null,
                    "default": null,
                    "length": 20,
                    "name": "id",
                    "type": "BIGINT",
                    "unsigned": true,
                    "values": [],
                    "zerofill": false
                },
                "title": {
                    "allow_null": true,
                    "auto_increment": false,
                    "binary": false,
                    "collation": "utf8mb4_unicode_ci",
                    "comment": "",
                    "decimals": null,
                    "default": null,
                    "length": 255,
                    "name": "title",
                    "type": "VARCHAR",
                    "unsigned": false,
                    "values": [],
                    "zerofill": false
                },
                "user_id": {
                    "allow_null": true,
                    "auto_increment": false,
                    "binary": false,
                    "comment": "",
                    "decimals": null,
                    "default": null,
                    "length": 20,
                    "name": "user_id",
                    "type": "INT",
                    "unsigned": false,
                    "values": [],
                    "zerofill": false
                }
            },
            "indexes": {
                "PRIMARY": {
                    "type": "primary",
                    "name": "PRIMARY",
                    "length": [
                        null
                    ],
                    "columns": [
                        "id"
                    ]
                }
            }
        },
        {
            "name": "todolist_tasks",
            "columns": {
                "id": {
                    "allow_null": false,
                    "auto_increment": true,
                    "binary": false,
                    "comment": "",
                    "decimals": null,
                    "default": null,
                    "length": 20,
                    "name": "id",
                    "type": "BIGINT",
                    "unsigned": true,
                    "values": [],
                    "zerofill": false
                },
                "title": {
                    "allow_null": true,
                    "auto_increment": false,
                    "binary": false,
                    "collation": "utf8mb4_unicode_ci",
                    "comment": "",
                    "decimals": null,
                    "default": null,
                    "length": 255,
                    "name": "title",
                    "type": "VARCHAR",
                    "unsigned": false,
                    "values": [],
                    "zerofill": false
                },
                "list_id": {
                    "allow_null": true,
                    "auto_increment": false,
                    "binary": false,
                    "comment": "",
                    "decimals": null,
                    "default": null,
                    "length": 20,
                    "name": "list_id",
                    "type": "INT",
                    "unsigned": false,
                    "values": [],
                    "zerofill": false
                },
                "status": {
                    "allow_null": true,
                    "auto_increment": false,
                    "binary": false,
                    "collation": "utf8mb4_unicode_ci",
                    "comment": "",
                    "decimals": null,
                    "default": null,
                    "length": 32,
                    "name": "status",
                    "type": "VARCHAR",
                    "unsigned": false,
                    "values": [],
                    "zerofill": false
                }
            },
            "indexes": {
                "PRIMARY": {
                    "type": "primary",
                    "name": "PRIMARY",
                    "length": [
                        null
                    ],
                    "columns": [
                        "id"
                    ]
                }
            }
        }
    ]
}
JSON;
