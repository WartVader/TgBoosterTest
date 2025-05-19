<template>
  <div class="rules">
    <div class="">
      Если
    </div>
    <div class="rules__block">
      <div class="rules__list">
        <div v-for="(condition, index) in conditions" :key="index"
             class="rules__item">
          <div v-if="index > 0" class="rules__logic-operator" @click="toggleOperator(condition)">
            {{ logicalOperatorLabel(condition.logicalOperator) }}
            <input type="text" v-model="condition.logicalOperator" hidden>
          </div>
          <div class="input-block">
            <button @click="removeCondition(index)" class="rules__remove-btn" v-if="index > 0">
              ×
            </button>
            <select v-model="condition.column" class="input rules__input-column" required>
              <option :value="null" disabled hidden>Выберите параметр</option>
              <option
                v-for="(label, value) in ruleParams.conditions.columns"
                :key="value"
                :value="value"
              >
                {{ capitalize(label) }}
              </option>
            </select>

            <select v-model="condition.operator" class="input">
              <option value=">">></option>
              <option value="<"><</option>
              <option value="=">=</option>
              <option value=">=">≥</option>
              <option value="<=">≤</option>
            </select>

            <input v-model="condition.value" type="number" class="input" placeholder="Значение">
          </div>

        </div>
      </div>
      <button @click="addCondition" class="rules__add-btn">
        + Добавить условие
      </button>
    </div>
    <div class="">
      То
    </div>
    <div class="rules__block">
      <div v-for="(action, index) in actions" :key="index"
           class="rules__item">
        <div class="input-block">
          <select v-model="action.operation" class="input" required>
            <option
              v-for="(label, value) in ruleParams.actions.operations"
              :key="value"
              :value="value"
            >
              {{ capitalize(label) }}
            </option>
          </select>
          <select v-model="action.column" class="input rules__input-column" required>
            <option :value="null" disabled selected hidden>Выберите вариант</option>
            <option
              v-for="(label, value) in ruleParams.actions.columns"
              :key="value"
              :value="value"
            >
              {{ capitalize(label) }}
            </option>
          </select>
          <input v-model="action.amount" type="number" class="input" placeholder="Значение" min="0">
        </div>

      </div>
      <button
        @click="submitRule"
        :disabled="isSubmitting"
        class="submit-btn"
      >
        {{ isSubmitting ? 'Сохранение...' : 'Сохранить' }}
      </button>
      <div v-if="submitStatus" class="status-message" :class="submitStatus.type">
        {{ submitStatus }}
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { capitalize } from "vue";

export default {
  props: {
    id: {
      default: null,
      required: false
    }
  },
  data() {
    return {
      conditions: [
        {
          column: null,
          operator: '<',
          value: 0,
          logicalOperator: 'and'
        },
      ],
      actions: [
        {
          column: null,
          operation: null,
          amount: 0
        }
      ],
      ruleParams: {
        conditions: {
          columns: {}
        },
        actions: {
          columns: {},
          operations: {}
        }
      },
      isSubmitting: false,
      submitStatus: null,
      loadStatus: null
    }
  },
  async beforeMount() {
    await this.fetchRuleParams();
    await this.getRule(this.id)
  },
  methods: {
    toggleOperator(condition) {
      condition.logicalOperator = condition.logicalOperator === 'and' ? 'or' : 'and';
    },
    logicalOperatorLabel(operator) {
      if (operator === 'and')
        return 'И';
      else if (operator === 'or')
        return 'ИЛИ';
    },
    capitalize(string) {
      return capitalize(string);
    },
    async fetchRuleParams() {
      try {
        const response = await axios.get('/api/rules/params');
        this.ruleParams = response.data;
        this.setActionsDefault(response.data)
      } catch (error) {
        this.error = error.response?.data?.message || error.message;
      }
    },
    setActionsDefault(response) {
      this.conditions[0].column = Object.keys(response.conditions.columns)[0];
      this.actions[0].column = Object.keys(response.actions.columns)[0];
      this.actions[0].operation = Object.keys(response.actions.operations)[0];
    },
    addCondition() {
      this.conditions.push({
        column: Object.keys(this.ruleParams.conditions.columns)[0],
        operator: '<',
        value: 0,
        logicalOperator: 'and'
      })
    },
    removeCondition(index) {
      this.conditions.splice(index, 1)
    },
    validateRule() {
      for (const condition of this.conditions) {
        if (condition.value === '' || condition.value === null) {
          return 'Заполните все значения условий';
        }
        if (condition.column === '') {
          return 'Заполните все параметры условий';
        }
      }

      for (const action of this.actions) {
        if (action.value === '' || action.value === null) {
          return 'Заполните все значения действий';
        }
      }

      return true;
    },
    async getRule(id) {
      if (id) {
        try {
          const response = await axios.get('/api/rules/' + id);
          this.conditions = response.data.data.conditions;
          this.actions = response.data.data.actions;
        } catch (e) {

        }
      }
    },
    async submitRule() {
      this.isSubmitting = true;
      let validation = this.validateRule();

      if (validation !== true) {
        this.submitStatus = validation;
        this.isSubmitting = false;
        return;
      }

      try {
        const ruleData = {
          conditions: this.conditions,
          actions: this.actions
        };

        let response = null;

        if (this.id) {
          response = await axios.put('/api/rules/' + this.id, ruleData);
        } else {
          response = await axios.post('/api/rules', ruleData);
        }

        this.submitStatus = response.data;

        if (this.id === null) {
          this.resetForm();
        }

      } catch (error) {
        console.error('Ошибка сохранения:', error);
        this.submitStatus = error.message;
      }
      this.isSubmitting = false;
    },
    resetForm() {
      this.conditions = [
        {
          column: null,
          operator: '=',
          value: 0,
          logicalOperator: 'and'
        }
      ];
      this.actions = [
        {
          column: Object.keys(this.ruleParams.actions.columns)[0],
          operation: Object.keys(this.ruleParams.actions.operations)[0],
          amount: 0
        }
      ];
    }
  }
}
</script>

<style>
.rules {
  display: inline-block;
}
.input {
  border: 1px solid #9ca3af;
  border-radius: 3px;
  padding: 0.5rem;
  display: block;
  margin-right: 0.4rem;
  background-color: transparent;
  font-size: 0.9rem;
}
.rules__logic-operator {
  font-size: 0.9rem;
  cursor: pointer;
  font-weight: bold;
  width: 100%;
  text-align: center;
}
.rules__block {
  padding-left: 3rem;
  margin-top: 0.5rem;
  display: flex;
  flex-direction: column;
  position: relative;
}
.rules__item, .rules__logic-operator {
  margin-bottom: 0.5rem;
}
.input-block {
  display: flex;
}
.input-block .input:last-child {
  margin-right: 0;
}
.rules__remove-btn {
  background: transparent;
  border: none;
  display: block;
  cursor: pointer;
  opacity: 70%;
  font-size: 1.3rem;
  position: absolute;
  left: 0;
  margin-left: 1rem;
}
.rules__item {
  display: flex;
  flex-direction: column;
}
.rules__add-btn {
  background-color: transparent;
  border: none;
  cursor: pointer;
  color: #3b82f6;
  font-weight: bold;
  font-size: 0.9rem;
}
.rules__input-column {
  width: 100%;
}

.submit-btn {
  background-color: #3b82f6;
  color: white;
  border: #3b82f6;
  border-radius: 3px;
  display: block;
  font-size: 1rem;
  padding: 0.5rem;
  cursor: pointer;
  margin-top: 1rem;
}
.rules .submit-btn {
  margin-bottom: 0.5rem;
}
</style>
