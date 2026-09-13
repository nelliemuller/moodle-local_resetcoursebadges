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
 * Permission-controlled bulk course badge cleanup page.
 *
 * @package   local_resetcoursebadges
 * @copyright 2026 Nellie Deutsch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/badgeslib.php');

$courseid = required_param('courseid', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

$course = get_course($courseid);
$context = context_course::instance($courseid);

require_login($course);
require_capability('moodle/badges:deletebadge', $context);

$url = new moodle_url('/local/resetcoursebadges/cleanup.php', ['courseid' => $courseid]);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->set_title(get_string('bulkcleanuptitle', 'local_resetcoursebadges'));
$PAGE->set_heading(format_string($course->fullname));

if ($confirm) {
    require_sesskey();
    $result = \local_resetcoursebadges\observer::remove_outdated_course_badges($courseid);

    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('bulkcleanupcomplete', 'local_resetcoursebadges'));
    echo $OUTPUT->notification(
        get_string('bulkcleanupresult', 'local_resetcoursebadges', (object)$result),
        \core\output\notification::NOTIFY_SUCCESS
    );
    echo $OUTPUT->continue_button(new moodle_url('/badges/index.php', [
        'type' => BADGE_TYPE_COURSE,
        'id' => $courseid,
    ]));
    echo $OUTPUT->footer();
    exit;
}

$counts = $DB->get_record_sql(
    'SELECT COUNT(b.id) AS total,
            SUM(CASE WHEN bi.badgeid IS NULL THEN 1 ELSE 0 END) AS unissued
       FROM {badge} b
  LEFT JOIN (SELECT DISTINCT badgeid FROM {badge_issued}) bi ON bi.badgeid = b.id
      WHERE b.type = :type AND b.courseid = :courseid',
    ['type' => BADGE_TYPE_COURSE, 'courseid' => $courseid]
);

$data = (object)[
    'course' => format_string($course->fullname),
    'total' => (int)($counts->total ?? 0),
    'unissued' => (int)($counts->unissued ?? 0),
    'issued' => (int)($counts->total ?? 0) - (int)($counts->unissued ?? 0),
];

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('bulkcleanuptitle', 'local_resetcoursebadges'));
echo $OUTPUT->confirm(
    get_string('bulkcleanupconfirm', 'local_resetcoursebadges', $data),
    new moodle_url($url, ['confirm' => 1, 'sesskey' => sesskey()]),
    new moodle_url('/badges/index.php', ['type' => BADGE_TYPE_COURSE, 'id' => $courseid])
);
echo $OUTPUT->footer();
