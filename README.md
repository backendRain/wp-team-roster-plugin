# Advanced Team Roster WordPress Plugin

A custom WordPress plugin built from scratch to manage and display a filterable team roster. The plugin uses a custom REST API to serve data to a dynamic, client-side rendered front end.

## Project Overview

This plugin provides a complete solution for managing and displaying a "Meet the Team" page. It creates a dedicated "Team Members" section in the WordPress admin for easy content management and uses a simple `[team_members]` shortcode to render a clean, responsive, and interactive grid on the front end.

This project was built to demonstrate a full-stack understanding of modern WordPress development, from back-end PHP architecture to front-end JavaScript interactivity.

## Core Features

*   **Custom REST API Endpoint:** Registers a custom `/wp-json/team/v1/members` endpoint to serve all team member information as a clean JSON object, decoupling the back end from the front end.
*   **JavaScript-driven Rendering:** The front end uses the native `fetch()` API to asynchronously retrieve data. The team member cards and filter buttons are then dynamically generated and rendered on the client side.
*   **Dynamic Filtering:** Filter buttons are automatically created based on the categories assigned to team members. Adding a new category requires no changes to the code.
*   **Smooth UX Transitions:** When filtering, team members gracefully fade and scale in and out of view using CSS transitions, providing a fluid user experience.
*   **WordPress Best Practices:** Follows official WordPress standards for creating custom post types, registering REST routes, and correctly enqueuing scripts and styles.

 ## Technology Stack

*   **Back End:** PHP, WordPress Plugin API, WordPress REST API
*   **Front End:** Vanilla JavaScript (ES6+), CSS3 (Flexbox/Grid), HTML5
*   **Development Environment:** Local by Flywheel

## Usage

1.  Install and activate the plugin.
2.  Navigate to the "Team Members" menu in the WordPress admin to add new members.
3.  Assign a `category` (e.g., "design", "developer") to each member using the Custom Fields meta box.
4.  Place the `[team_members]` shortcode on any page or post to display the roster.
