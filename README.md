# madvic WordPress Scripts

This add-on is a `mu-plugin` for WordPress, many hooks for basic customize.

- `madvic_wp_scripts_security.php` is security hooks
- `madvic_wp_scripts_tunning.php` is customize hooks

## Security

- Head cleaning (Remove wp_generator, wlwmanifest_link, rsd_link, xmlrpc_enabled)
- Hide connections errors
- Delete script version
- Filter body_class in order to hide User ID and User nicename
- No french punctuation and accents for filename

## Tunning

- Hide update notifications
- Deactive admin bar
- Does not display the previous and next link ( `<link rel='prev'` ... and `<link rel='next'` ...)
- Head cleanning (Remove `start_post_rel_link`, `feed_links_extra`, `feed_links`, `wp_shortlink_wp_head`, `index_rel_link`, `parent_post_rel_link`)
- Deactivate somes default Widgets
- Minify HTML
- Remove h1 from the WordPress editor
- Add medium format `medium_large` to media in admin
- Deactivate API

