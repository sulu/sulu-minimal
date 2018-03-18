// @flow
import React from 'react';
import Integer from './Integer';
import Select from './Select';
import Text from './Text';

const TYPE_MAP = {
    text: Text,
    integer: Integer,
    select: Select,
};

export default class Attribute extends React.Component<*> {
    render() {
        const {
            attribute: {
                code,
                name,
                type,
                value,
                configuration,
            },
            onChange,
        } = this.props;

        if (!TYPE_MAP[type]) {
            return <p>{type}</p>;
        }

        const Component = TYPE_MAP[type];

        return (
            <div>
                <label>{name}</label>
                <Component
                    value={value}
                    configuration={configuration}
                    code={code}
                    onChange={onChange}
                />
            </div>
        );
    }
}
