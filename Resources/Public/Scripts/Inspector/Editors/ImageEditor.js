/**
 * Extended version of the Neos ImageEditor that sends the current node along
 */
define(
[
	'Content/Inspector/Editors/ImageEditor'
],
function (ImageEditor) {

	return ImageEditor.extend({
		_uploaderInitialized: function () {
			this._super();
			var that = this;
			this._uploader.bind('BeforeUpload', function(uploader) {
				uploader.settings.multipart_params['__node'] = that.get('inspector.nodeSelection.selectedNode.nodePath');
			});
        },
    });
});
