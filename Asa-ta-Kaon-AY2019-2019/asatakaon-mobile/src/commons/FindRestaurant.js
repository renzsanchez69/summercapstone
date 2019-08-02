import React, { Component } from "react";
import {
  Platform,
  StyleSheet,
  Text,
  View,
  AsyncStorage,
  Image,
  NetInfo,
  TouchableWithoutFeedback,
  TextInput,
  FlatList
} from "react-native";
import { Container, Icon, Spinner } from "native-base";
import MapView from "react-native-maps";
import { Marker } from "react-native-maps";
import geolib from "geolib";

/* -- Custom Components  -- */

import Constants from "./Constants.js";
const anonymousUserIcon = require("../img/icon/anonymous-user.png");

export default class FindRestaurant extends Component {
  state = {
    registeredRestaurants: [],
    tracksViewChangesUserIcon: true,
    tracksViewChangesResIcon: true,
    pressedRestaurantDetails: {},
    pressedCreatedMenu: [],
    restaurantObjectListener: "",
    goSearch: false,
    searchInput: "",
    searchResults: [],
    filteredResults: []
  };

  onLoadIcon = () => {
    if (anonymousUserIcon) {
      const timeoutID = setTimeout(() => {
        this.setState({ tracksViewChangesUserIcon: false });
        console.log("set to false");
      }, 1500);
      clearTimeout(timeoutID);
    }
  };

  componentDidMount() {
    this.getAllRegisteredRestaurants();
  }

  getAllRegisteredRestaurants = () => {
    this.props.doUseFirebaseObject
      .database()
      .ref("RESTAURANT/")
      .once("value", snapshot => {
        if (snapshot.exists()) {
          const initRegisteredRestaurants = [];
          const allRestaurantsWithKey = JSON.parse(
            JSON.stringify(snapshot.val())
          );
          Object.keys(allRestaurantsWithKey).forEach(resKey => {
            initRegisteredRestaurants.push(allRestaurantsWithKey[resKey]);
          });
          this.setState({ registeredRestaurants: initRegisteredRestaurants });
        }
      });
  };

  containPressRestaurantDetails = restaurant => {
    if (!restaurant.Menu) {
      this.setState({ pressedRestaurantDetails: restaurant });
      return;
    } else this.setState({ pressedRestaurantDetails: restaurant });

    const menuWithKey = JSON.parse(JSON.stringify(restaurant.Menu));
    const initPressedCreatedMenu = [];
    Object.keys(menuWithKey).forEach(menKeys => {
      initPressedCreatedMenu.push(menuWithKey[menKeys]);
    });
    this.setState({ pressedCreatedMenu: initPressedCreatedMenu });
  };

  displayAllApprovedRestaurants = () => {
    const approvedRestaurants = [];
    const usersLat = this.props.doGetUsersLocation.latitude;
    const usersLong = this.props.doGetUsersLocation.longitude;

    this.state.searchResults.forEach(restaurant => {
      if (
        restaurant.placeStatus == Constants.RESTAURANT_PLACE_STATUS.ACCEPTED &&
        restaurant.location
      ) {
        const jsonLocation = JSON.parse(JSON.stringify(restaurant.location));
        const distance = Number(
          geolib.getDistance(
            {
              latitude: Number(jsonLocation.latitude),
              longitude: Number(jsonLocation.longitude)
            },
            {
              latitude: Number(usersLat),
              longitude: Number(usersLong)
            }
          )
        );
        console.log(distance);
        if (distance <= 1000)
          approvedRestaurants.push(
            <Marker
              onPress={() => this.containPressRestaurantDetails(restaurant)}
              tracksViewChanges={false}
              coordinate={{
                latitude: jsonLocation.latitude,
                longitude: jsonLocation.longitude
              }}
              title={restaurant.restaurantName}
              description={restaurant.restaurantName}
            />
          );
      }
    });
    return approvedRestaurants;
  };

  getMapDisplay = () => {
    if (this.props.doGetUsersLocation.latitude) {
      return (
        <MapView
          style={{ height: "100%", width: "100%" }}
          provider={MapView.PROVIDER_GOOGLE}
          region={{
            latitude: this.props.doGetUsersLocation.latitude,
            longitude: this.props.doGetUsersLocation.longitude,
            latitudeDelta: 0.0822 * 0.4,
            longitudeDelta: 0.0321 * 0.4
          }}
        >
          <Marker
            coordinate={{
              latitude: this.props.doGetUsersLocation.latitude,
              longitude: this.props.doGetUsersLocation.longitude
            }}
            tracksViewChanges={this.state.tracksViewChangesUserIcon}
            title={"Hello Anonymous!"}
            description={"Here is your location, register now!"}
          >
            <Image
              onLoad={this.onLoadIcon}
              source={anonymousUserIcon}
              style={{ height: 35, width: 35 }}
            />
          </Marker>
          {this.displayAllApprovedRestaurants()}
        </MapView>
      );
    } else {
      return (
        <Text
          style={{
            height: "6%",
            position: "relative",
            top: "45%",
            width: "100%",
            fontSize: 15,
            fontWeight: "bold",
            textAlign: "center",
            textAlignVertical: "center",
            color: "#000"
          }}
        >
          Getting your location in map, please wait..
        </Text>
      );
    }
  };
  snapToLocation = (restaurantName,latitude,longitude) => {
    if (this.latitude && this.longitude) {
      return (
        <MapView
          style={{ height: "100%", width: "100%" }}
          provider={MapView.PROVIDER_GOOGLE}
          region={{
            latitude: this.latitude,
            longitude: this.longitude,
            latitudeDelta: parseInt(latitude) * 0.03,
            longitudeDelta: parseInt(longitude) * 0.03
          }}
        >
          <Marker
            coordinate={{
              latitude: this.latitude,
              longitude: this.longitude
            }}
            tracksViewChanges={this.state.tracksViewChangesUserIcon}
            title={this.restaurantName}
            description={this.restaurantName}
          >
          </Marker>
        </MapView>
      );
    } else {
      return (
        <Text
          style={{
            height: "6%",
            position: "relative",
            top: "45%",
            width: "100%",
            fontSize: 15,
            fontWeight: "bold",
            textAlign: "center",
            textAlignVertical: "center",
            color: "#000"
          }}
        >
          Display unavailable for the moment
        </Text>
      );
    }
  };
  showDetailsOfRestaurant = () => {
    if (this.state.pressedRestaurantDetails.location) {
      return (
        <View
          style={{
            height: 295,
            width: "75%",
            position: "absolute",
            borderColor: "#ddd",
            borderBottomWidth: 0,
            shadowColor: "#000",
            shadowOffset: {
              width: 0,
              height: 5
            },
            shadowOpacity: 0.34,
            shadowRadius: 6.27,
            elevation: 10,
            backgroundColor: "#fff",
            top: "35%",
            borderRadius: 15,
            alignItems: "center"
          }}
        >
          <Text
            style={{
              height: "9%",
              width: "100%",
              textAlign: "center",
              textAlignVertical: "center",
              fontWeight: "bold",
              fontSize: 14,
              color: "#000",
              top: "6%"
            }}
          >
            {this.state.pressedRestaurantDetails.restaurantName}
          </Text>
          <Text
            style={{
              height: "8.5%",
              width: "100%",
              textAlign: "center",
              textAlignVertical: "center",
              fontSize: 12,
              color: "#000",
              top: "6%"
            }}
          >
            {"Operating hour: " +
              this.state.pressedRestaurantDetails.startingHour +
              "-" +
              this.state.pressedRestaurantDetails.closingHour}
          </Text>
          <Text
            style={{
              height: "8.5%",
              width: "100%",
              textAlign: "center",
              textAlignVertical: "center",
              fontSize: 12,
              color: "#000",
              top: "6%",
              fontWeight: "bold"
            }}
          >
            {this.state.pressedRestaurantDetails.acceptBooking == "true"
              ? "We accept Bookings"
              : "Does not accept bookings"}
          </Text>
          <Text
            style={{
              height: "17%",
              width: "95%",
              textAlign: "center",
              textAlignVertical: "center",
              fontSize: 11,
              color: "#000",
              top: "6%"
            }}
          >
            {"Complete Address: " +
              this.state.pressedRestaurantDetails.location.addressName}
          </Text>
          <Text
            style={{
              height: "7%",
              width: "100%",
              textAlign: "center",
              textAlignVertical: "center",
              fontSize: 11.5,
              color: "#000",
              top: "6%",
              fontWeight: "bold"
            }}
          >
            {"Price range in pesos: " +
              (this.state.pressedRestaurantDetails.priceRange
                ? this.state.pressedRestaurantDetails.priceRange.minimum +
                  "-" +
                  this.state.pressedRestaurantDetails.priceRange.maximum
                : "not yet updated")}
          </Text>
          <Text
            style={{
              height: "7%",
              width: "90%",
              textAlignVertical: "center",
              fontSize: 12,
              color: "#000",
              top: "3%",
              fontStyle: "italic",
              fontWeight: "bold"
            }}
          >
            {"Our Menu - "}
          </Text>
          {this.state.pressedRestaurantDetails.Menu ? (
            <View
              style={{
                height: "36%",
                width: "100%",
                position: "relative",
                top: "3%"
              }}
            >
              <FlatList
                data={this.state.pressedCreatedMenu}
                renderItem={({ item }) => (
                  <View
                    style={{
                      height: 73,
                      width: "100%",
                      position: "relative",
                      marginBottom: 10,
                      marginTop: 10,
                      borderBottomWidth: 2
                    }}
                  >
                    <Text
                      style={{
                        height: "20%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 10.5,
                        color: "#000",
                        fontWeight: "bold"
                      }}
                    >
                      {item.name}
                    </Text>
                    <Text
                      style={{
                        height: "20%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 10.5,
                        color: "#000"
                      }}
                    >
                      {item.description.length == 0
                        ? "No added description"
                        : item.description}
                    </Text>
                    <Text
                      style={{
                        height: "20%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 10.5,
                        color: "#000"
                      }}
                    >
                      {"Price in pesos: " + item.price}
                    </Text>
                    <Text
                      style={{
                        height: "20%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 10.5,
                        color: "#000"
                      }}
                    >
                      {item.foodType}
                    </Text>
                    <Text
                      style={{
                        height: "20%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 10.5,
                        color: "#000"
                      }}
                    >
                      {"Good for " + item.persons}
                    </Text>
                  </View>
                )}
                keyExtractor={item => item.key}
              />
            </View>
          ) : (
            <Text
              style={{
                height: "8.5%",
                width: "90%",
                textAlignVertical: "center",
                fontSize: 12,
                color: "#000",
                top: "10%",
                fontStyle: "italic",
                fontWeight: "bold",
                textAlignVertical: "center",
                textAlign: "center"
              }}
            >
              {"No dish added yet"}
            </Text>
          )}
          <TouchableWithoutFeedback
            onPress={() => this.setState({ pressedRestaurantDetails: {} })}
          >
            <Text
              style={{
                height: "10%",
                width: "12%",
                position: "absolute",
                top: "2%",
                left: "2%",
                textAlign: "center",
                textAlignVertical: "center"
              }}
            >
              <Icon
                style={{ fontSize: 19 }}
                name="closesquare"
                type="AntDesign"
              />
            </Text>
          </TouchableWithoutFeedback>
          <Text
            style={{
              height: "10%",
              width: "11%",
              position: "absolute",
              top: "90%",
              left: "2%",
              textAlign: "center",
              textAlignVertical: "center"
            }}
          >
            <Icon style={{ fontSize: 17 }} name="caretdown" type="AntDesign" />
          </Text>
        </View>
      );
    } else return;
  };

  doSearchResults = async () => {
    const stringToSearch = String(this.state.searchInput).toLowerCase();
    const initSearchResults = [];
    const initFilteredRestaurants = [];
    const usersLat = this.props.doGetUsersLocation.latitude;
    const usersLong = this.props.doGetUsersLocation.longitude;

    this.state.registeredRestaurants.forEach(res => {
      let availableString = `${res.restaurantName} ${
        res.location ? res.location.addressName : ""
      }  ${
        res.priceRange
          ? String(res.priceRange.minimum) + String(res.priceRange.maximum)
          : ""
      }`;

      if (
        String(availableString)
          .toLowerCase()
          .indexOf(stringToSearch) >= 0 &&
        stringToSearch.length !== 0 &&
        res.placeStatus == Constants.RESTAURANT_PLACE_STATUS.ACCEPTED &&
        res.location &&
        (parseInt(res.priceRange.minimum,10) <= Number(this.state.searchInput) &&
        parseInt(res.priceRange.maximum,10) >= Number(this.state.searchInput))
      ) {
        const jsonLocation = JSON.parse(JSON.stringify(res.location));
        const distance = Number(
          geolib.getDistance(
            {
              latitude: Number(jsonLocation.latitude),
              longitude: Number(jsonLocation.longitude)
            },
            {
              latitude: Number(usersLat),
              longitude: Number(usersLong)
            }
          )
        );
        if (distance <= 200) {
          initSearchResults.push(res);
        }
      }
    });
    await this.setState({ goSearch: true, searchResults: initSearchResults });
  };

  showSearchedRestaurant = () => {
    if (this.state.goSearch === true) {
      return (
        <View
          style={{
            height: 293,
            width: "75%",
            position: "absolute",
            borderColor: "#ddd",
            borderBottomWidth: 0,
            shadowColor: "#000",
            shadowOffset: {
              width: 0,
              height: 5
            },
            shadowOpacity: 0.34,
            shadowRadius: 6.27,
            elevation: 10,
            backgroundColor: "#fff",
            top: "35%",
            borderRadius: 15,
            alignItems: "center"
          }}
        >
          {this.state.searchResults.length <= 0 ? (
            <Text
              style={{
                height: "8.5%",
                width: "100%",
                textAlign: "center",
                textAlignVertical: "center",
                fontSize: 12,
                color: "#000",
                top: "6%"
              }}
            >
              {"No results found"}
            </Text>
          ) : (
            <View
              style={{
                height: "95%",
                width: "95%",
                position: "relative",
                top: "7%",
                paddingBottom: 20
              }}
            >
              <FlatList
                data={this.state.searchResults}
                renderItem={({ item }) => (
                  <TouchableWithoutFeedback onPress={() =>this.snapToLocation(item.restaurantName, item.latitude,item.longitude)
                  }>
                  <View
                    style={{
                      height: 120,
                      width: "100%",
                      position: "relative",
                      marginBottom: 10
                    }}
                  >
                    <Text
                      style={{
                        height: "15%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontWeight: "bold",
                        fontSize: 11,
                        color: "red",
                        top: "6%"
                      }}
                    >
                      {item.restaurantName}
                    </Text>
                    <Text
                      style={{
                        height: "15%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 12,
                        color: "#000",
                        top: "6%"
                      }}
                    >
                      {"Operating hour: " +
                        item.startingHour +
                        "-" +
                        item.closingHour}
                    </Text>
                    <Text
                      style={{
                        height: "15%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 12,
                        color: "#000",
                        top: "6%",
                        fontWeight: "bold"
                      }}
                    >
                      {item.acceptBooking == "true"
                        ? "We accept Bookings"
                        : "Does not accept bookings"}
                    </Text>
                    <Text
                      style={{
                        height: "15%",
                        width: "100%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 11.5,
                        color: "#000",
                        top: "6%",
                        fontWeight: "bold"
                      }}
                    >
                      {"Price range in pesos: " +
                        (item.priceRange
                          ? item.priceRange.minimum +
                            "-" +
                            item.priceRange.maximum
                          : "not yet updated")}
                    </Text>
                    <Text
                      style={{
                        height: "35%",
                        width: "95%",
                        textAlign: "center",
                        textAlignVertical: "center",
                        fontSize: 11,
                        color: "#000",
                        top: "6%"
                      }}
                    >
                      {"Complete Address: " + item.location.addressName}
                    </Text>
                  </View>
                  </TouchableWithoutFeedback>
                )}
                keyExtractor={item => item.key}
              />
            </View>
            
          )}

          <TouchableWithoutFeedback
            onPress={() => this.setState({ goSearch: false })}
          >
            <Text
              style={{
                height: "10%",
                width: "12%",
                position: "absolute",
                top: "2%",
                left: "2%",
                textAlign: "center",
                textAlignVertical: "center"
              }}
            >
              <Icon
                style={{ fontSize: 19 }}
                name="closesquare"
                type="AntDesign"
              />
            </Text>
          </TouchableWithoutFeedback>
        </View>
      );
    } else {
      return null;
    }
  };

  render() {
    return (
      <React.Fragment>
        <View
          style={{
            height: "9%",
            width: "100%",
            position: "absolute",
            backgroundColor: "#555dff"
          }}
        >
          <TouchableWithoutFeedback
            onPress={() =>
              this.props.doChangeMainAppDisplay(
                Constants.APP_PAGES.WELCOME_APP_PAGE
              )
            }
          >
            <Text
              style={{
                height: "50%",
                width: "18%",
                position: "relative",
                borderWidth: 2,
                color: "#000",
                fontSize: 13,
                fontWeight: "bold",
                textAlign: "center",
                textAlignVertical: "center",
                borderRadius: 100,
                top: "20%",
                left: "2%"
              }}
            >
              RETURN
            </Text>
          </TouchableWithoutFeedback>
        </View>

        <View
          style={{
            height: "100%",
            width: "100%",
            alignItems: "center"
          }}
        >
          <View
            style={{
              backgroundColor: "#fff",
              height: "91%",
              top: "9%",
              width: "100%",
              position: "relative"
            }}
          >
            {this.getMapDisplay()}
            {this.state.goSearch == true && this.getMapDisplay()}
          </View>

          <TouchableWithoutFeedback
            onPress={() =>
              this.props.doChangeMainAppDisplay(
                Constants.APP_PAGES.LOGIN_APP_PAGE
              )
            }
          >
            <Text
              style={{
                borderColor: "#ddd",
                borderBottomWidth: 0,
                shadowColor: "#000",
                shadowOffset: {
                  width: 0,
                  height: 5
                },
                shadowOpacity: 0.34,
                shadowRadius: 6.27,
                elevation: 10,
                backgroundColor: "#fff",
                height: 74,
                width: 74,
                borderRadius: 100,
                position: "absolute",
                left: "76%",
                top: "4%",
                fontWeight: "bold",
                textAlign: "center",
                textAlignVertical: "center",
                backgroundColor: "#fff",
                color: "#000",
                fontSize: 12
              }}
            >
              <Icon
                style={{
                  fontSize: 30,
                  fontWeight: "bold"
                }}
                name="login"
                type="AntDesign"
              />
              {"\n"}
              LOGIN
            </Text>
          </TouchableWithoutFeedback>

          <TouchableWithoutFeedback
            onPress={() =>
              this.props.doChangeMainAppDisplay(
                Constants.APP_PAGES.SIGN_APP_PAGE
              )
            }
          >
            <Text
              style={{
                borderColor: "#ddd",
                borderBottomWidth: 0,
                shadowColor: "#000",
                shadowOffset: {
                  width: 0,
                  height: 5
                },
                shadowOpacity: 0.34,
                shadowRadius: 6.27,
                elevation: 10,
                backgroundColor: "#fff",
                height: 74,
                width: 74,
                borderRadius: 100,
                position: "absolute",
                left: "53.7%",
                top: "4%",
                fontWeight: "bold",
                textAlign: "center",
                textAlignVertical: "center",
                backgroundColor: "#fff",
                color: "#000",
                fontSize: 12
              }}
            >
              <Icon
                style={{
                  fontSize: 30,
                  fontWeight: "bold"
                }}
                name="user-plus"
                type="FontAwesome"
              />
              {"\n"}
              USER
            </Text>
          </TouchableWithoutFeedback>

          <TouchableWithoutFeedback
            onPress={() =>
              this.props.doChangeMainAppDisplay(
                Constants.APP_PAGES.SIGN_RESTAURANT
              )
            }
          >
            <Text
              style={{
                borderColor: "#ddd",
                borderBottomWidth: 0,
                shadowColor: "#000",
                shadowOffset: {
                  width: 0,
                  height: 5
                },
                shadowOpacity: 0.34,
                shadowRadius: 6.27,
                elevation: 10,
                backgroundColor: "#fff",
                height: 74,
                width: 74,
                borderRadius: 100,
                position: "absolute",
                left: "31%",
                top: "4%",
                fontWeight: "bold",
                textAlign: "center",
                textAlignVertical: "center",
                backgroundColor: "#fff",
                color: "#000",
                fontSize: 11
              }}
            >
              <Icon
                style={{
                  fontSize: 30,
                  fontWeight: "bold"
                }}
                name="food-fork-drink"
                type="MaterialCommunityIcons"
              />
              {"\n"}
              OWNER
            </Text>
          </TouchableWithoutFeedback>
          <View
            style={{
              height: 50,
              width: "70%",
              borderRadius: 15,
              position: "absolute",
              borderColor: "#ddd",
              borderBottomWidth: 0,
              shadowColor: "#000",
              shadowOffset: {
                width: 0,
                height: 5
              },
              shadowOpacity: 0.34,
              shadowRadius: 6.27,
              elevation: 10,
              backgroundColor: "#fff",
              left: "15%",
              top: 110,
              flexDirection: "row"
            }}
          >
            <Text
              style={{
                position: "relative",
                height: "90%",
                width: "15%",
                fontSize: 15,
                color: "#000",
                left: "20%",
                textAlign: "center",
                textAlignVertical: "center"
              }}
            >
              <Icon
                style={{ fontSize: 20, color: "#000" }}
                name="search"
                type="FontAwesome"
              />
            </Text>
            <View
              style={{
                height: "90%",
                width: "65%",
                left: "20%",
                position: "relative",
                borderBottomWidth: 2,
                borderColor: "#000"
              }}
            >
              <TextInput
                placeholder="Find food places(name and price)"
                style={{
                  height: "100%",
                  width: "100%",
                  position: "relative",
                  fontSize: 13,
                  textAlignVertical: "center",
                  paddingLeft: "2%",
                  color: "#000"
                }}
                onChangeText={searchInput => this.setState({ searchInput })}
              />
            </View>
            <TouchableWithoutFeedback onPress={() => this.doSearchResults()}>
              <Text
                style={{
                  height: "90%",
                  width: "15%",
                  fontSize: 13,
                  left: "20%",
                  color: "#000",
                  textAlignVertical: "center",
                  paddingTop: "2%",
                  textAlign: "center"
                }}
              >
                Go
              </Text>
            </TouchableWithoutFeedback>
          </View>
          {this.showDetailsOfRestaurant()}
          {this.showSearchedRestaurant()}
        </View>
      </React.Fragment>
    );
  }
}
