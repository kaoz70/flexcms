<md-tabs md-dynamic-height="" md-border-bottom="">
    <md-tab label="Editor">
        <textarea rows="15" cols="5" class="form-control" ng-model="contentModel" ui-tinymce="tinymceOptions" ></textarea>
    </md-tab>
    <md-tab label="C&oacute;digo">
        <ui-codemirror ui-codemirror-opts="editorOptions" ng-model="contentModel"></ui-codemirror>
    </md-tab>
</md-tabs>