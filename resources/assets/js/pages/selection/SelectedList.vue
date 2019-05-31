<template>
  <page-layout title="Course List">
    <transition name="page-toggle">
      <a-card
        style="margin-top: 24px"
        :bordered="false"
        title="Selected Courses"
        v-if="currentUser.type=='0'"
      >
        <div slot="extra">{{this.pagination.total}} Courses</div>
        <a-spin tip="Loading..." :spinning="loading">
          <a-list size="large">
            <a-list-item v-for="pageResult in result" :key="pageResult.id">
              <a-list-item-meta :description="pageResult.course.location">
                <a-icon slot="avatar" style="fontSize: 48px" type="book" theme="twoTone"/>
                <a slot="title">{{pageResult.course.name}}</a>
              </a-list-item-meta>
              <div slot="actions">
                <a @click="handleCancel(pageResult.id)">Cancel</a>
              </div>
              <div class="list-content">
                <div class="list-content-item">
                  <span>Teacher</span>
                  <p>{{pageResult.course.teacher}}</p>
                </div>
                <div class="list-content-item">
                  <span>Time</span>
                  <p>{{pageResult.course.time}}</p>
                </div>
                <div class="list-content-item">
                  <span>Capacity</span>
                  <p>{{pageResult.course.capacity}}</p>
                </div>
                <div class="list-content-item">
                  <span>Available</span>
                  <p>{{pageResult.course.available}}</p>
                </div>
              </div>
            </a-list-item>
          </a-list>
        </a-spin>
        <div class="list-pagination">
          <a-pagination
            v-model="currentPage"
            @change="handlePageChange"
            :total="this.pagination.total"
            :pageSize="this.pagination.per_page"
          />
        </div>
      </a-card>
      <a-card
        style="margin-top: 24px"
        :bordered="false"
        title="Selected Courses"
        v-else-if="currentUser.type=='1'"
      >
        <div slot="extra">{{this.pagination.total}} Courses</div>
        <a-spin tip="Loading..." :spinning="loading">
          <a-list size="large">
            <a-list-item v-for="pageResult in result" :key="pageResult.id">
              <a-list-item-meta :description="pageResult.location">
                <a-icon slot="avatar" style="fontSize: 48px" type="book" theme="twoTone"/>
                <a slot="title">{{pageResult.name}}</a>
              </a-list-item-meta>
              <div slot="actions">
                <a @click="handleDelete(pageResult.id)">Delete</a>
              </div>
              <div class="list-content">
                <div class="list-content-item">
                  <span>Time</span>
                  <p>{{pageResult.time}}</p>
                </div>
                <div class="list-content-item">
                  <span>Capacity</span>
                  <p>{{pageResult.capacity}}</p>
                </div>
                <div class="list-content-item">
                  <span>Available</span>
                  <p>{{pageResult.available}}</p>
                </div>
              </div>
            </a-list-item>
          </a-list>
        </a-spin>
        <div class="list-pagination">
          <a-pagination
            v-model="currentPage"
            @change="handlePageChange"
            :total="this.pagination.total"
            :pageSize="this.pagination.per_page"
          />
        </div>
      </a-card>
    </transition>
  </page-layout>
</template>

<script>
import PageLayout from "../../layouts/PageLayout";
import ACard from "ant-design-vue/es/card/Card";
import ARow from "ant-design-vue/es/grid/Row";
import ACol from "ant-design-vue/es/grid/Col";
import HeadInfo from "../../components/tool/HeadInfo";
import AButton from "ant-design-vue/es/button/button";
import AList from "ant-design-vue/es/list/index";
import AListItem from "ant-design-vue/es/list/Item";
import AAvatar from "ant-design-vue/es/avatar/Avatar";
import AProgress from "ant-design-vue/es/progress";
import ADropdown from "ant-design-vue/es/dropdown";
import AMenu from "ant-design-vue/es/menu/index";
import AIcon from "ant-design-vue/es/icon/icon";
import AButtonGroup from "ant-design-vue/es/button/button-group";
import AInput from "ant-design-vue/es/input/Input";
import AInputSearch from "ant-design-vue/es/input/Search";
import ARadioGroup from "ant-design-vue/es/radio/Group";
import ARadio from "ant-design-vue/es/radio";
import APagination from "ant-design-vue/es/pagination";
import ASpin from "ant-design-vue/es/spin/Spin";
import ATag from "ant-design-vue/es/tag/Tag";
import ATooltip from "ant-design-vue/es/tooltip/Tooltip";
import { getCookie } from "tiny-cookie";

const AListItemMeta = AListItem.Meta;
const AMenuItem = AMenu.Item;
const ARadioButton = ARadio.Button;
export default {
  name: "SelectedList",
  components: {
    PageLayout,
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
    ACard,
    APagination,
    ASpin,
    ATag,
    ATooltip
  },
  created() {
    this.getPage(1);
    var currentUser = this.currentUser;
  },
  data() {
    return {
      currentUser: JSON.parse(getCookie("user")),
      filter: "all",
      loading: false,
      currentPage: 1,
      result: {},
      pagination: {}
    };
  },
  methods: {
    getPage(page) {
      this.loading = true;
      var t = this;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + getCookie("token");
      if (this.currentUser.type == '0') {
        this.$axios
        .get(
          "/api/user/" +
            this.currentUser.id +
            "/selections?include=course&page=" +
            page
        )
        .then(function(response) {
          t.result = response.data.data;
          t.pagination = response.data.meta.pagination;
          t.loading = false;
        })
        .catch(err => {
          console.log("Error: " + JSON.stringify(err));
        });
      } else {
        this.$axios
        .get(
          "/api/user/" +
            this.currentUser.id +
            "/courses?page=" +
            page
        )
        .then(function(response) {
          t.result = response.data.data;
          t.pagination = response.data.meta.pagination;
          t.loading = false;
        })
        .catch(err => {
          console.log("Error: " + JSON.stringify(err));
        });
      }
    },
    handlePageChange(page, pageSize) {
      this.currentPage = page;
      this.getPage(page, this.filter);
    },
    handleCancel(id) {
      this.$axios
        .delete("/api/selection/" + id)
        .then(resp => {
          let res = resp.data;
          if (resp.status == 200) {
            this.$message.success("Cancelled Successfully");
            this.getPage(1);
          } else {
            this.$message.error("Cancel Failed");
            this.getPage(1);
            console.log("Error: " + JSON.stringify(res));
          }
        })
        .catch(err => {
          this.$message.error(err.response.data.message);
          this.getPage(1);
          console.log("Error: " + JSON.stringify(err));
        });
    },
    handleDelete(id) {
      this.$axios
        .delete("/api/course/" + id)
        .then(resp => {
          let res = resp.data;
          if (resp.status == 200) {
            this.$message.success("Deleted Successfully");
            this.getPage(1);
          } else {
            this.$message.error("Delete Failed");
            this.getPage(1);
            console.log("Error: " + JSON.stringify(res));
          }
        })
        .catch(err => {
          this.$message.error(err.response.data.message);
          this.getPage(1);
          console.log("Error: " + JSON.stringify(err));
        });
    }
  }
};
</script>

<style lang="less" scoped>
.ant-avatar-lg {
  width: 48px;
  height: 48px;
  line-height: 48px;
}

.list-content-item {
  color: rgba(0, 0, 0, 0.45);
  display: inline-block;
  vertical-align: middle;
  font-size: 14px;
  margin-left: 40px;
  span {
    line-height: 20px;
  }
  p {
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
<style>
.ant-spin-blur {
  filter: blur(3px);
}
</style>
