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
 * Local callbacks for Reset Course Badges.
 *
 * @package   local_resetcoursebadges
 * @copyright 2026 Nellie Deutsch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Adds the bulk cleanup action to course navigation for badge managers.
 *
 * @param navigation_node $navigation Course navigation node.
 * @param stdClass $course Course record.
 * @param context_course $context Course context.
 * @return void
 */
function local_resetcoursebadges_extend_navigation_course(
    navigation_node $navigation,
    stdClass $course,
    context_course $context
): void {
    if (!has_capability('moodle/badges:deletebadge', $context)) {
        return;
    }

    $url = new moodle_url('/local/resetcoursebadges/cleanup.php', ['courseid' => $course->id]);
    $navigation->add(
        get_string('bulkcleanuplink', 'local_resetcoursebadges'),
        $url,
        navigation_node::TYPE_SETTING,
        null,
        'local_resetcoursebadges_cleanup'
    );
}
