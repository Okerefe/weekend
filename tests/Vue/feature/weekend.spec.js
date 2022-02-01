/**
 * @jest-environment jsdom
 */
import { mount } from '@vue/test-utils'
import Weekend from '../../../resources/js/Components/Weekend.vue'
import {beforeEach} from "@jest/globals";

describe('Weekend Component:', () => {

    let wrapper;
    beforeEach(function () {
        wrapper = mount(Weekend, {
            propsData: {
                searchByText: "Search By",
                fields: ["id","name","username"],
            }
        })

    })

    test('Displays "Search By" Text Correctly', () => {
        // expect(wrapper.find('')
        const searchInputWrapper = wrapper.get('.searchByText');
        expect(searchInputWrapper.element.value).toBe("Search By");
    })

    test('Displays All Search Fields in Select Tag', () => {
        expect(wrapper.find('option:nth-of-type(1)').element.value).toBe('id');
        expect(wrapper.find('option:nth-of-type(2)').element.value).toBe('name');
        expect(wrapper.find('option:nth-of-type(3)').element.value).toBe('username');
    })

});
