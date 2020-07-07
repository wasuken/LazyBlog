global.fetch = require('node-fetch');
import TagList from '../components/TagList';
import { mount } from '@vue/test-utils';
import fetchMock from 'fetch-mock';

fetchMock.get(
	'/api/tag',
	[{"name":"JavaScript"},
	 {"name":"PHP"},
	 {"name":"Ruby"},
	 {"name":"Nim"},
	 {"name":"Rust"}]
);

describe('TagList', () => {
	const wrapper = mount(TagList);

	it('タグをきちんと取得できてるか。', () => {
		expect(wrapper.html()).toContain('<button>JavaScript</button>');
		expect(wrapper.html()).toContain('<button>PHP</button>');
		expect(wrapper.html()).toContain('<button>Ruby</button>');
		expect(wrapper.html()).toContain('<button>Nim</button>');
		expect(wrapper.html()).toContain('<button>Rust</button>');
	});
})
