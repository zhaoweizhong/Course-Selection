<template>
  <div>
    <a-list size="large">
        <a-list-item v-for="searchResult in result" :key="searchResult.id">
          <a-list-item-meta
            :description="'Location: ' + searchResult.location"
          >
          <a-icon slot="avatar" style="fontSize: 48px" type="book" theme="twoTone"/> 
          <a slot="title">{{searchResult.name}}</a>
          </a-list-item-meta>
          <div slot="actions">
            <a @click="handleSelect(searchResult.id)">Select</a>
          </div>
          <div class="list-content">
            <div class="list-content-item">
              <span>Teacher</span>
              <p>{{searchResult.teacher}}</p>
            </div>
            <div class="list-content-item">
              <span>Time</span>
              <p>{{searchResult.time}}</p>
            </div>
            <div class="list-content-item">
              <span>Capacity</span>
              <p>{{searchResult.capacity}}</p>
            </div>
            <div class="list-content-item">
              <span>Available</span>
              <p>{{searchResult.available}}</p>
            </div>
          </div>
        </a-list-item>
      </a-list>
    <div class="list-pagination">
        <a-pagination v-model="currentPage" @change="handlePageChange" :total="this.pagination.total" :pageSize="this.pagination.per_page"/>
    </div>
  </div>
</template>

<script>
import ARow from 'ant-design-vue/es/grid/Row'
import ACol from 'ant-design-vue/es/grid/Col'
import HeadInfo from '../../components/tool/HeadInfo'
import AButton from 'ant-design-vue/es/button/button'
import AList from 'ant-design-vue/es/list/index'
import AListItem from 'ant-design-vue/es/list/Item'
import AAvatar from 'ant-design-vue/es/avatar/Avatar'
import AProgress from 'ant-design-vue/es/progress'
import ADropdown from 'ant-design-vue/es/dropdown'
import AMenu from 'ant-design-vue/es/menu/index'
import AIcon from 'ant-design-vue/es/icon/icon'
import AButtonGroup from 'ant-design-vue/es/button/button-group'
import AInput from 'ant-design-vue/es/input/Input'
import AInputSearch from 'ant-design-vue/es/input/Search'
import ARadioGroup from 'ant-design-vue/es/radio/Group'
import ARadio from 'ant-design-vue/es/radio'
import APagination from 'ant-design-vue/es/pagination'

const AListItemMeta = AListItem.Meta
const AMenuItem = AMenu.Item
const ARadioButton = ARadio.Button

export default {
  name: 'SearchList',
  components: {
    ARadioButton,
    ARadio,
    ARadioGroup,
    AInputSearch,
    AInput,
    AButtonGroup,
    AIcon,
    AMenuItem,
    AMenu,
    ADropdown,
    AProgress,
    AAvatar,
    AListItemMeta,
    AListItem,
    AList,
    AButton,
    HeadInfo,
    ACol,
    ARow,
    APagination},
  created() {
    this.doSearch(1)
  },
  props: {
    fatherKeyword: {
      type: String,
      default: () => {
        return ''
      }
    }
  },
  data() {
    return {
      keyword: this.fatherKeyword,
      currentPage: 1,
      detail: {},
      result: {},
      pagination: {},
    }
  },
  methods: {
    doSearch (page) {
      this.$emit('loading' , true);
      var t = this
      var formData = new FormData();
      formData.append("keyword", this.keyword);
      this.$axios
      .post("/api/courses/search?page=" + page, formData)
      .then(resp => {
        console.log("resp " + JSON.stringify(resp));
        if (resp.status == 200) {
          t.result = resp.data.data
          t.detail = resp.data.meta.detail
          t.pagination = resp.data.meta.pagination
          t.$emit('loading' , false);
        } else {
          console.log("Error: " + JSON.stringify(res));
        }
      })
      .catch(err => {
        console.log("Error: " + JSON.stringify(err));
      });
    },
    handleSelect(id) {
      var t = this
      var formData = new FormData();
      formData.append("course_id", id);
      this.$axios
          .post("/api/selections/", formData)
          .then(resp => {
            let res = resp.data;
            if (resp.status == 201) {
              t.$message.success("Selected Successfully");
            } else {
              t.$message.error("Selection Failed");
              console.log("Error: " + JSON.stringify(res));
            }
          })
          .catch(err => {
            t.$message.error(err.response.data.message);
            console.log("Error: " + JSON.stringify(err));
          });
    },
    redirectProfile (id) {
      this.$router.push('/user/' + id)
    },
    redirectMessage (sid) {
      this.$router.push('/messages/new/' + sid)
    },
    redirectAppointment (sid) {
      this.$router.push('/appointments/new/' + sid)
    },
    handlePageChange (page, pageSize) {
      this.currentPage = page,
      this.doSearch(page)
    }
  },
  watch: {
    '$route' (to, from) {
      this.keyword = to.params.keyword
      this.doSearch(1)
    }
  },
}
</script>


<style lang="less" scoped>
.list-content-item{
    color: rgba(0,0,0,.45);
    display: inline-block;
    vertical-align: middle;
    font-size: 14px;
    margin-left: 40px;
    span{
      line-height: 20px;
    }
    p{
      margin-top: 4px;
      margin-bottom: 0;
      line-height: 22px;
    }
  }
  .list-pagination {
    margin-top: 15px;
    text-align: center;
  }
</style>
