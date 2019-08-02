import React, { Component } from "react";
import { Text, View, Image, TouchableWithoutFeedback } from "react-native";
/* -- Custom Components  -- */

import Constants from "./Constants.js";

export default class WelcomePage extends Component {
  render() {
    return (
      <React.Fragment>
        <View
          style={{
            height: "100%",
            width: "100%",
            position: "absolute",
            backgroundColor: "#555dff"
          }}
        />
        <Image
          source={require("../img/background.png")}
          style={{
            height: "48%",
            width: "75%",
            position: "absolute",
            resizeMode: "cover",
            top: "11%",
            left: "12.5%"
          }}
        />

        <View
          style={{
            height: "100%",
            width: "100%"
          }}
        >
          <View
            style={{
              height: "40%",
              width: "90%",
              left: "5%",
              position: "relative",
              top: "45%",
              alignItems: "center"
            }}
          >
            <TouchableWithoutFeedback
              onPress={() =>
                this.props.doChangeMainAppDisplay(
                  Constants.APP_PAGES.FIND_RESTAURANT_APP
                )
              }
            >
              <Text
                style={{
                  borderRadius: 2,
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
                  height: "20%",
                  width: "50%",
                  position: "relative",
                  top: "42%",
                  fontSize: 15,
                  color: "#000",
                  textAlign: "center",
                  textAlignVertical: "center",
                  borderRadius: 100,
                  color: "#000",
                  fontWeight: "900"
                }}
              >
                Find Restaurant
              </Text>
            </TouchableWithoutFeedback>

            <TouchableWithoutFeedback
              onPress={() =>
                this.props.doChangeMainAppDisplay(
                  Constants.APP_PAGES.OUR_MANUAL
                )
              }
            >
              <Text
                style={{
                  borderRadius: 2,
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
                  height: "20%",
                  width: "50%",
                  position: "relative",
                  top: "50%",
                  fontSize: 15,
                  textAlign: "center",
                  textAlignVertical: "center",
                  borderRadius: 100,
                  color: "#000",
                  fontWeight: "900"
                }}
              >
                Terms & Conditions
              </Text>
            </TouchableWithoutFeedback>
          </View>
        </View>
      </React.Fragment>
    );
  }
}
