import Model from 'flarum/Model';
import mixin from 'flarum/utils/mixin';

export default class DecontaminatorRule extends mixin(Model, {
    type: Model.attribute('type'),
    name: Model.attribute('name'),
    regex: Model.attribute('regex'),
    replacement: Model.attribute('replacement'),
    flag: Model.attribute('flag'),
    event: Model.attribute('event'),
    time: Model.attribute('time', Model.transformDate),
    editTime: Model.attribute('editTime', Model.transformDate),
}) {}
