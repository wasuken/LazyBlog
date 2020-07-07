global.fetch = require('node-fetch');
import CommentList from '../components/CommentList';
import { mount } from '@vue/test-utils';
import fetchMock from 'fetch-mock';

fetchMock.get(
	'/api/comment',
	[{"page_id":"101",
	  "handle_name":"hoge",
	  "comment":"unti",
	  "updated_at":"2020-02-08 21:45:15"},
	 {"page_id":"49",
	  "handle_name":"unchi",
	  "comment":"hoge762",
	  "updated_at":"2019-12-26 20:39:38"},
	 {"page_id":"100",
	  "handle_name":"unchi",
	  "comment":"hoge763",
	  "updated_at":"2019-12-26 20:39:38"}]
);

describe('CommentList', () => {
	const wrapper = mount(CommentList);

	it('コメントをきちんと取得できてるか。', () => {
		expect(wrapper.html()).toContain('<small>hoge</small>');
		expect(wrapper.html()).toContain('<small>unchi</small>');
	});
})
