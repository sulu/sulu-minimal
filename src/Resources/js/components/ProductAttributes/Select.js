// @flow
import React from 'react';

export default class Text extends React.Component<*> {
    handleChange = (event) => {
        const {
            code,
            onChange,
        } = this.props;

        let value = [event.target.value];

        onChange(code, value);
    };

    render() {
        const {
            value,
            configuration,
        } = this.props;
        const locale = 'en';

        return (
            <select multiple={configuration.multiple} onChange={this.handleChange}>
                {Object.keys(configuration.choices).map((key) => {
                    return (
                        <option
                            value={key}
                            selected={value && value.indexOf(key) > -1}
                        >
                            {configuration.choices[key][locale]}
                        </option>
                    );
                })}
            </select>
        );
    }
}
