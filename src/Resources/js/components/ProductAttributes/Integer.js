// @flow
import React from 'react';

export default class Integer extends React.Component<*> {
    handleChange = (event) => {
        const {
            code,
            onChange,
        } = this.props;

        onChange(code, parseInt(event.target.value));
    };

    render() {
        const {
            value,
        } = this.props;

        return <input type="integer" value={value} onChange={this.handleChange}/>;
    }
}
