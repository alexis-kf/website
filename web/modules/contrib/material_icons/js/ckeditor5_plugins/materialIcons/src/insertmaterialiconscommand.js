/**
 * @file defines InsertMaterialIconsCommand, which is executed when the icon
 * toolbar button is pressed.
 */
// cSpell:ignore simpleboxediting

import { Command } from 'ckeditor5/src/core';

export default class InsertMaterialIconsCommand extends Command {
  execute(settings) {
    this.editor.model.change((writer) => {

      let classes = 'material-icons'
      if (settings.family !== 'baseline') {
        classes += ' material-icons-' + settings.family;
      }

      if (settings.classes !== '') {
        classes += ' ' + settings.classes;
      }

      const attributes = {
        class: classes,
      };

      const content = writer.createElement('materialIcon', attributes);
      const docFrag = writer.createDocumentFragment();
      writer.append(content, docFrag);
      writer.insertText(settings.icon, content);
      this.editor.model.insertContent(docFrag);
    });
  }

  refresh() {
    const model = this.editor.model;
    const selection = model.document.selection;
    const allowedIn = model.schema.findAllowedParent(
      selection.getFirstPosition(),
      'materialIcon',
    );
    this.isEnabled = allowedIn !== null;
  }
}
