// @flow
import React from 'react';
import {observer} from 'mobx-react';
import {action, observable} from 'mobx';
import AutoComplete from 'sulu-admin-bundle/components/AutoComplete/AutoComplete';
import Suggestion from 'sulu-admin-bundle/components/AutoComplete/Suggestion';
import Requester from 'sulu-admin-bundle/services/Requester/Requester';

@observer
export default class AttributeAutoComplete extends React.Component<*> {
    @observable loading = false;
    @observable suggestions = [];
    handleSearch = (query: string) => {
        this.setLoading(true);

        const locale = 'en'; // FIXME get locale

        Requester.get('/admin/api/attributes?locale=' + locale + '&search=' + query).then((response) => {
            this.setSuggestions(response._embedded.attributes);
            this.setLoading(false);
        });
    };

    handleChange = (value) => {
        this.props.onChange(value);
        this.setSuggestions([]);
    };

    handleResponse = (response) => {
        this.setSuggestions(response._embedded.attributes);
        this.setLoading(false);
    };

    @action setLoading = (loading) => {
        this.loading = loading;
    };

    @action setSuggestions = (suggestions) => {
        this.suggestions = suggestions;
    };

    render() {
        return (
            <AutoComplete
                value={null}
                onSearch={this.handleSearch}
                onChange={this.handleChange}
                loading={this.loading}
            >
                {this.suggestions.map((suggestion, index) => {
                    return (
                        <Suggestion
                            key={index}
                            icon="fa-ticket"
                            value={suggestion}
                        >
                            {(highlight) => (
                                <div>{highlight(suggestion.code)}</div>
                            )}
                        </Suggestion>
                    );
                })}
            </AutoComplete>
        );
    }
}
