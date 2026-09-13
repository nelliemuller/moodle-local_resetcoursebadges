<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Command-line bulk cleanup for one course.
 *
 * @package   local_resetcoursebadges
 * @copyright 2026 Nellie Deutsch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

[$options, $unrecognised] = cli_get_params(
    [
        'courseid' => null,
        'help' => false,
    ],
    [
        'c' => 'courseid',
        'h' => 'help',
    ]
);

if ($unrecognised) {
    cli_error('Unknown option: ' . implode(' ', $unrecognised));
}

if ($options['help'] || empty($options['courseid'])) {
echo "Remove outdated badge definitions from one reused course while keeping issued user badges.\n\n";
    echo "Options:\n";
    echo "--courseid=ID, -c=ID   Exact Moodle course ID (required)\n";
    echo "--help, -h             Show this help\n\n";
    echo "Example:\n";
    echo "php local/resetcoursebadges/cli/cleanup.php --courseid=32\n";
    exit($options['help'] ? 0 : 1);
}

$courseid = clean_param($options['courseid'], PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], 'id,fullname', MUST_EXIST);

echo "Course: {$course->fullname} (ID {$course->id})\n";
$result = \local_resetcoursebadges\observer::remove_outdated_course_badges($course->id);
echo "Deleted unissued badge definitions: {$result['deleted']}\n";
echo "Archived badge definitions with issued user badges preserved: {$result['archived']}\n";
