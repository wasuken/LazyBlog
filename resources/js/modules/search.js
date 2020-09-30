const state = {
	lastQueryParams: {
		q: "",
		tag: "",
		writer: "",
		sortKey: "",
		order: "",
		count: 30,
		current: 1,
		pb: "",
		pe: "",
	},
	lastSearchResultAllPageCount: 0,
	searchResult: [],
};

const mutations = {
	setResult(state, searchResult){
		state.searchResult = searchResult.data;
		state.lastSearchResultAllPageCount = searchResult.pageCount;
	},
	setLastQueryParams(state, lastQueryParams){
		state.lastQueryParams = lastQueryParams;
	}
};

const getters = {
	getResult(state){
		return {
			searchResult: state.searchResult,
			lastSearchResultAllPageCount: state.lastSearchResultAllPageCount,
		};
	},
	getSearchResult(state){
		return state.searchResult;
	},
	getLastSearchResultAllPageCount(state){
		return state.LastSearchResultAllCount;
	},
	getLastQueryParams(state){
		return state.lastQueryParams;
	}
};

const actions = {
	// これいる？
	// fetchResult({commit}, query){
	// 	fetch(encodeURI('/api/page?' + query))
	// 		.then(result => result.json)
	// }
};

export default {
    namespaced: true,
    state,
    mutations,
	actions,
	getters,
};
