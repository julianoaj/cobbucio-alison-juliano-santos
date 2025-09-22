import { defineStore } from 'pinia';
import * as state from './states';
import * as getters from './getters';
import * as actions from './actions';

type HomeStore = typeof state & typeof getters & typeof actions

export const useHomeStore = defineStore('home', (): HomeStore => ({
    ...state,
    ...getters,
    ...actions,
}));

export type { HomeStore };
