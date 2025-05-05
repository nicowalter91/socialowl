C:\XAMPP\HTDOCS\SOCIAL_APP
│   .gitignore
│   composer.json
│   composer.lock
│   readme.txt
│
├───assets
│   ├───css
│   │       bootstrap-icons.css
│   │       bootstrap.min.css
│   │       style.css
│   │
│   ├───fonts
│   │       bootstrap-icons.woff
│   │       bootstrap-icons.woff2
│   │
│   ├───img
│   │   │   default_header.png
│   │   │   Owl_logo.svg
│   │   │   owl_pattern.png
│   │   │   profil.png
│   │   │   social_owl_chat.png
│   │   │   social_owl_page.png
│   │   │
│   │   └───website
│   ├───js
│   │   │   bootstrap.bundle.min.js
│   │   │   notifications.js
│   │   │   script.js
│   │   │
│   │   └───modules
│   │           chat-handler.js
│   │           comment-handler.js
│   │           emoji-handler.js
│   │           live-updates.js
│   │           media-preview-handler.js
│   │           notification-handler.js
│   │           notification-stream-handler.js
│   │           post-edit-handler.js
│   │           post-handler.js
│   │           search-handler.js
│   │           theme-handler.js
│   │
│   ├───posts
│   │       media_680b36e52b3358.05635391.webp
│   │       media_680f121e2b30f3.87621037.webp
│   │       media_680f4a85e2c470.15911394.jpg
│   │       media_68108568a342e7.24357583.png
│   │
│   └───uploads
│           andrea94_profile_1745487853.jpg
│           default_header.png
│           nico91_header_1745401324.png
│           nico91_header_1745401527.png
│           nico91_header_1745401686.jpg
│           nico91_header_1745562677.png
│           nico91_header_1745828984.jpg
│           nico91_profile_1745401324.jpg
│           nico91_profile_1745562677.jpg
│           profil.png
│
├───controllers
│   │   add_comment.php
│   │   create_comment.php
│   │   create_post.php
│   │   delete_comment.php
│   │   delete_post.php
│   │   feed.php
│   │   follow_user.php
│   │   like_comment.php
│   │   like_post.php
│   │   login.php
│   │   logout.php
│   │   profil-update.php
│   │   register.php
│   │   reset_mail_send.php
│   │   reset_pwd.php
│   │   search.post.php
│   │   unfollow_user.php
│   │   update_comment.php
│   │
│   └───api
│           chat_delete.php
│           chat_followers.php
│           chat_messages.php
│           chat_send.php
│           chat_unread.php
│           comments_since.php
│           create_log_tables.php
│           create_tables.php
│           delete_notification.php
│           deletion_stream.php
│           edit_stream.php
│           event_stream.php
│           following_update.php
│           follow_request_action.php
│           follow_stream.php
│           notifications.php
│           notify_deletion.php
│           notify_edit.php
│           posts_since.php
│           search_users.php
│           updates_since.php
│           update_notifications_table.php
│
├───includes
│       auth.php
│       chat_service.php
│       config.php
│       connection.php
│       profile.helper.php
│
├───models
│       comment.php
│       follow.php
│       like.php
│       post.php
│       user.php
│
├───partials
│       comment_item.php
│       modal-chat.php
│       modal-delete-posts.php
│       modal-profil.php
│       navbar.php
│       navbar_minimal.php
│       post-form.php
│       post_card.php
│       sidebar-left.php
│       sidebar-right.php
│
├───ps
│       setup-tests.ps1
│
├───sql
│       notifications.sql
│
├───src
├───tests
│       test_commentDuplication.php
│       test_create_comment.php
│       test_create_post.php
│       test_like_post.php
│       test_login_valid.php
│
├───trash
├───vendor
│   │   autoload.php
│   │
│   ├───composer
│   │       autoload_classmap.php
│   │       autoload_namespaces.php
│   │       autoload_psr4.php
│   │       autoload_real.php
│   │       autoload_static.php
│   │       ClassLoader.php
│   │       installed.json
│   │       installed.php
│   │       InstalledVersions.php
│   │       LICENSE
│   │       platform_check.php
│   │
│   └───phpmailer
│       └───phpmailer
│           │   .codecov.yml
│           │   .editorconfig
│           │   .gitattributes
│           │   .gitignore
│           │   changelog.md
│           │   COMMITMENT
│           │   composer.json
│           │   get_oauth_token.php
│           │   LICENSE
│           │   phpcs.xml.dist
│           │   phpdoc.dist.xml
│           │   phpunit.xml.dist
│           │   README.md
│           │   SECURITY.md
│           │   SMTPUTF8.md
│           │   UPGRADING.md
│           │   VERSION
│           │
│           ├───.github
│           │   │   dependabot.yml
│           │   │   FUNDING.yml
│           │   │
│           │   ├───actions
│           │   │   └───build-docs
│           │   │           Dockerfile
│           │   │           entrypoint.sh
│           │   │
│           │   ├───ISSUE_TEMPLATE
│           │   │       bug_report.md
│           │   │
│           │   └───workflows
│           │           docs.yaml
│           │           scorecards.yml
│           │           tests.yml
│           │
│           ├───.phan
│           │       config.php
│           │
│           ├───docs
│           │       README.md
│           │
│           ├───examples
│           │   │   azure_xoauth2.phps
│           │   │   callback.phps
│           │   │   contactform-ajax.phps
│           │   │   contactform.phps
│           │   │   contents.html
│           │   │   contentsutf8.html
│           │   │   DKIM_gen_keys.phps
│           │   │   DKIM_sign.phps
│           │   │   exceptions.phps
│           │   │   extending.phps
│           │   │   gmail.phps
│           │   │   gmail_xoauth.phps
│           │   │   mail.phps
│           │   │   mailing_list.phps
│           │   │   pop_before_smtp.phps
│           │   │   README.md
│           │   │   sendmail.phps
│           │   │   sendoauth2.phps
│           │   │   send_file_upload.phps
│           │   │   send_multiple_file_upload.phps
│           │   │   simple_contact_form.phps
│           │   │   smime_signed_mail.phps
│           │   │   smtp.phps
│           │   │   smtp_check.phps
│           │   │   smtp_low_memory.phps
│           │   │   smtp_no_auth.phps
│           │   │   ssl_options.phps
│           │   │
│           │   └───images
│           │           PHPMailer card logo.afdesign
│           │           PHPMailer card logo.png
│           │           PHPMailer card logo.svg
│           │           phpmailer.png
│           │           phpmailer_mini.png
│           │
│           ├───language
│           │       phpmailer.lang-af.php
│           │       phpmailer.lang-ar.php
│           │       phpmailer.lang-as.php
│           │       phpmailer.lang-az.php
│           │       phpmailer.lang-ba.php
│           │       phpmailer.lang-be.php
│           │       phpmailer.lang-bg.php
│           │       phpmailer.lang-bn.php
│           │       phpmailer.lang-ca.php
│           │       phpmailer.lang-cs.php
│           │       phpmailer.lang-da.php
│           │       phpmailer.lang-de.php
│           │       phpmailer.lang-el.php
│           │       phpmailer.lang-eo.php
│           │       phpmailer.lang-es.php
│           │       phpmailer.lang-et.php
│           │       phpmailer.lang-fa.php
│           │       phpmailer.lang-fi.php
│           │       phpmailer.lang-fo.php
│           │       phpmailer.lang-fr.php
│           │       phpmailer.lang-gl.php
│           │       phpmailer.lang-he.php
│           │       phpmailer.lang-hi.php
│           │       phpmailer.lang-hr.php
│           │       phpmailer.lang-hu.php
│           │       phpmailer.lang-hy.php
│           │       phpmailer.lang-id.php
│           │       phpmailer.lang-it.php
│           │       phpmailer.lang-ja.php
│           │       phpmailer.lang-ka.php
│           │       phpmailer.lang-ko.php
│           │       phpmailer.lang-ku.php
│           │       phpmailer.lang-lt.php
│           │       phpmailer.lang-lv.php
│           │       phpmailer.lang-mg.php
│           │       phpmailer.lang-mn.php
│           │       phpmailer.lang-ms.php
│           │       phpmailer.lang-nb.php
│           │       phpmailer.lang-nl.php
│           │       phpmailer.lang-pl.php
│           │       phpmailer.lang-pt.php
│           │       phpmailer.lang-pt_br.php
│           │       phpmailer.lang-ro.php
│           │       phpmailer.lang-ru.php
│           │       phpmailer.lang-si.php
│           │       phpmailer.lang-sk.php
│           │       phpmailer.lang-sl.php
│           │       phpmailer.lang-sr.php
│           │       phpmailer.lang-sr_latn.php
│           │       phpmailer.lang-sv.php
│           │       phpmailer.lang-tl.php
│           │       phpmailer.lang-tr.php
│           │       phpmailer.lang-uk.php
│           │       phpmailer.lang-ur.php
│           │       phpmailer.lang-vi.php
│           │       phpmailer.lang-zh.php
│           │       phpmailer.lang-zh_cn.php
│           │
│           ├───src
│           │       DSNConfigurator.php
│           │       Exception.php
│           │       OAuth.php
│           │       OAuthTokenProvider.php
│           │       PHPMailer.php
│           │       POP3.php
│           │       SMTP.php
│           │
│           └───test
│               │   DebugLogTestListener.php
│               │   fakepopserver.sh
│               │   fakesendmail.sh
│               │   PreSendTestCase.php
│               │   runfakepopserver.sh
│               │   SendTestCase.php
│               │   testbootstrap-dist.php
│               │   TestCase.php
│               │   validators.php
│               │
│               ├───Fixtures
│               │   ├───FileIsAccessibleTest
│               │   │       accessible.txt
│               │   │       inaccessible.txt
│               │   │
│               │   └───LocalizationTest
│               │           phpmailer.lang-fr.php
│               │           phpmailer.lang-nl.php
│               │           phpmailer.lang-xa_scri_cc.php
│               │           phpmailer.lang-xb_scri.php
│               │           phpmailer.lang-xc_cc.php
│               │           phpmailer.lang-xd_cc.php
│               │           phpmailer.lang-xd_scri.php
│               │           phpmailer.lang-xe.php
│               │           phpmailer.lang-xx.php
│               │           phpmailer.lang-yy.php
│               │           phpmailer.lang-zz.php
│               │
│               ├───Language
│               │       TranslationCompletenessTest.php
│               │
│               ├───OAuth
│               │       OAuthTest.php
│               │
│               ├───PHPMailer
│               │       AddEmbeddedImageTest.php
│               │       AddrFormatTest.php
│               │       AddStringAttachmentTest.php
│               │       AddStringEmbeddedImageTest.php
│               │       AuthCRAMMD5Test.php
│               │       CustomHeaderTest.php
│               │       DKIMTest.php
│               │       DKIMWithoutExceptionsTest.php
│               │       DSNConfiguratorTest.php
│               │       EncodeQTest.php
│               │       EncodeStringTest.php
│               │       FileIsAccessibleTest.php
│               │       FilenameToTypeTest.php
│               │       GenerateIdTest.php
│               │       GetLastMessageIDTest.php
│               │       HasLineLongerThanMaxTest.php
│               │       Html2TextTest.php
│               │       ICalTest.php
│               │       IsPermittedPathTest.php
│               │       IsValidHostTest.php
│               │       LocalizationTest.php
│               │       MailTransportTest.php
│               │       MbPathinfoTest.php
│               │       MimeTypesTest.php
│               │       NormalizeBreaksTest.php
│               │       ParseAddressesTest.php
│               │       PHPMailerTest.php
│               │       PunyencodeAddressTest.php
│               │       QuotedStringTest.php
│               │       ReplyToGetSetClearTest.php
│               │       SetErrorTest.php
│               │       SetFromTest.php
│               │       SetTest.php
│               │       SetWordWrapTest.php
│               │       Utf8CharBoundaryTest.php
│               │       ValidateAddressCustomValidatorTest.php
│               │       ValidateAddressTest.php
│               │       WrapTextTest.php
│               │       XMailerTest.php
│               │
│               ├───POP3
│               │       PopBeforeSmtpTest.php
│               │
│               └───Security
│                       DenialOfServiceVectorsTest.php
│
└───views
        feed.view.php
        index.php
        login.view.php
        profile.php
        register.view.php
        search.view.php
        website.php