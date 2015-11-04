/**
 * Extended version of the Neos AssetEditor that sends the current node along
 */
define(
[
	'Content/Inspector/Editors/AssetEditor'
],
function (AssetEditor) {

	return AssetEditor.extend({
		_uploaderInitialized: function () {
			this._super();
			var that = this;
			this._uploader.bind('BeforeUpload', function(uploader) {
				uploader.settings.multipart_params['__node'] = that.get('inspector.nodeSelection.selectedNode.nodePath');
			});
		}
	});
});
