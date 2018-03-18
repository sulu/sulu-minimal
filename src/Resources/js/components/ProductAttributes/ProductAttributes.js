// @flow
import React from 'react';
import {observer} from 'mobx-react';
import {action} from 'mobx';
import Text from './Text';
import Integer from './Integer';
import Select from './Select';
import AttributesAutoComplete from './AttributesAutoComplete';
import Attribute from './Attribute';

@observer
export default class ProductAttributes extends React.Component<*> {
    handleChange = (code, attributeValue) => {
        const {
            value,
            onChange,
        } = this.props;

        let newValue = value.map((item) => {
            if (item.code === code) {
                item.value = attributeValue;
            }

            return item;
        });

        onChange(newValue);
    };

    @action
    onAddAttribute = (attribute) => {
        const {
            value,
            onChange,
        } = this.props;

        onChange([...value, attribute]);
    };

    render() {
        const {
            value,
        } = this.props;

        return (
            <div>
                <AttributesAutoComplete onChange={this.onAddAttribute}/>

                {value && value.map((attribute) => <Attribute onChange={this.handleChange} attribute={attribute}/>)}
            </div>
        );
    }
}
