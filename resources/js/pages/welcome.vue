<template>
  <div>
    <card title="Premier League Table">
      <table v-if="clubs" class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Team</th>
            <th scope="col">Played</th>
            <th scope="col">Won</th>
            <th scope="col">Drawn</th>
            <th scope="col">Lost</th>
            <th scope="col">
              <abbr title="Goals For">GF</abbr>
            </th>
            <th scope="col">
              <abbr title="Goals Against">GA</abbr>
            </th>
            <th scope="col">
              <abbr title="Goal Difference">GD</abbr>
            </th>
            <th scope="col">Points</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(club, index) in clubs" :key="club.id">
            <th scope="row">{{ parseInt(index) + 1 }}</th>
            <td>{{ club.name }}</td>
            <td>{{ club.played }}</td>
            <td>{{ club.won }}</td>
            <td>{{ club.drawn }}</td>
            <td>{{ club.lost }}</td>
            <td>{{ club.goals_for }}</td>
            <td>{{ club.goals_against }}</td>
            <td>{{ club.goals_difference }}</td>
            <td>{{ club.points }}</td>
          </tr>
        </tbody>
      </table>
      <table v-if="clubs" class="table">
        <thead>
          <tr>
            <th scope="col">Week {{ weekNumber }} match results</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="result in results" :key="result.id">
            <td>
              <p>
                {{ result.home.name }}
                <strong>{{ score(result.scores, result.home.id) }}</strong>
              </p>
              <p>
                {{ result.away.name }}
                <strong>{{ score(result.scores, result.away.id) }}</strong>
              </p>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="d-flex justify-content-between">
        <v-button @click.native="playAll()" :loading="loadingPlayAll">Play All</v-button>
        <v-button @click.native="nextWeek()" :loading="loadingNextWeek">Next Week</v-button>
      </div>
    </card>
    <card v-if="showPrediction" title="Premier League Table" class="mt-4">
      <table v-if="clubs" class="table">
        <thead>
          <tr>
            <th scope="col">{{ weekNumber }}th week prediction of champions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="club in clubs" :key="club.id">
            <td>{{ club.name }} - {{ club.chance }}%</td>
          </tr>
        </tbody>
      </table>
    </card>
  </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";

export default {
  layout: "default",

  metaInfo() {
    return { title: this.$t("Premier League") };
  },

  data: () => ({
    title: window.config.appName,
    clubs: null,
    loadingNextWeek: false,
    loadingPlayAll: false,
    showPrediction: false,
    weekNumber: 1,
    totalWeek: null,
    results: null
  }),

  created() {
    this.table();
    this.nextWeek();
  },

  methods: {
    async table() {
      const { data } = await axios.get("/api/standings");
      this.clubs = _.orderBy(
        data.clubs,
        ["points", "goals_difference"],
        ["desc", "asc"]
      );

      this.predictionTable();
    },

    async nextWeek() {
      this.loadingNextWeek = true;
      const { data } = await axios.get("/api/scores/next");
      this.results = data;
      console.log(this.results);
      this.table();
      this.loadingNextWeek = false;
    },

    async playAll() {
      this.loadingPlayAll = true;
      const { data } = await axios.get("/api/scores/all");
      this.table();
      this.loadingPlayAll = false;
    },

    predictionTable() {
      this.weekNumber = this.clubs[0].played;
      this.totalWeek = (parseInt(this.clubs.length) - 1) * 2;
      if (this.weekNumber > parseInt(this.clubs.length) - 1) {
        this.showPrediction = true;
      }
    },

    score(score, clubId) {
      return _.find(score, o => o.club_id === clubId).score;
    }
  }
};
</script>
