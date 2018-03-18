// @flow
import React from 'react';
import Text from './Text';
import Integer from './Integer';
import Select from './Select';
import Requester from 'sulu-admin-bundle/services/Requester/Requester';

const TYPE_MAP = {
    text: Text,
    integer: Integer,
    select: Select,
};

export default class ProductAttributes extends React.Component<*> {
    input;

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

    setInput = (input) => {
        this.input = input;
    };

    handleClick = () => {
        const locale = 'en'; // FIXME get locale

        Requester.get('/admin/api/attributes/' + this.input.value + '?locale=' + locale).then((response) => {
            const {
                value,
                onChange,
            } = this.props;

            let newValue = value.peek();

            newValue.push(response);

            onChange(newValue);

            this.input.value = '';
        });

        return false;
    };

    render() {
        const {
            value,
        } = this.props;

        return (
            <div>
                <div>
                    <input ref={this.setInput}/>
                    <button onClick={this.handleClick.bind(this)}>Add</button>
                </div>

                {value && value.map((attribute) => {
                    if (!TYPE_MAP[attribute.type]) {
                        return attribute.type;
                    }

                    const Component = TYPE_MAP[attribute.type];

                    return (
                        <div>
                            <label>{attribute.name}</label>
                            <Component
                                value={attribute.value}
                                configuration={attribute.configuration}
                                code={attribute.code}
                                onChange={this.handleChange}
                            />
                        </div>
                    );
                })}
            </div>
        );
    }
}
