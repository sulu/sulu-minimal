// @flow
import React from 'react';
import {observer} from 'mobx-react';
import {action} from 'mobx';
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

        if (!value) {
            return onChange([attribute]);
        }

        for (let key = 0; key < value.length; key++) {
            if (value[key].code === attribute.code) {
                return onChange(value);
            }
        }

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
