import { extend } from 'flarum/extend';
import app from 'flarum/app';
import CommentPost from 'flarum/components/CommentPost';

app.initializers.add('flarumite/decontaminator', () => {
    //! Very hacky. Doesn't work after editing post as the content will
    //! update but the footer won't, meaning this code isn't re-run
    extend(CommentPost.prototype, 'footerItems', function () {
        this.$('.Decontaminated-content:not([data-tooltip-created])').each((_, node) => {
            // Use App-content as container to prevent .Post-body overflow
            // hiding tooltips which are outside the post
            $(node).tooltip({ container: '#content > *' });
            // prevents making excess tooltips by ignoring tooltips that are
            // already made
            node.setAttribute('data-tooltip-created', '');
        });
    });
});
