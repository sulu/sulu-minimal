// @flow
import React from 'react';

export default class Text extends React.Component<*> {
    handleChange = (event) => {
        const {
            code,
            onChange,
        } = this.props;

        onChange(code, event.target.value);
    };

    render() {
        const {
            value,
        } = this.props;

        return <input type="text" value={value} onChange={this.handleChange}/>;
    }
}
